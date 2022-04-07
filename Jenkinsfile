    fu_score = 0;
    pipeline {
        agent {
            label 'sdlc-jenkins-agent'
        }
        environment {
            PATH = "${env.PATH}:${env.WORKSPACE}/vendor/bin:"
            PHP_VERSION = "8.1"
            DOCKER_REGISTRY_HOST = "https://registry.trickyplan.com"
            DOCKER_REGISTRY_PORT = 443
            REMOTE_REPOSITORY = "git@src.trickyplan.com:Codeine/Codeine.git"
            REMOTE_BRANCH = "master"
            LOCAL_DIRECTORY = "/src/codeine"
            DISTRIBUTION_FILES = "src docs tests CODEOWNERS *.md example.env"
            PROJECT_HAS_PHP = "On"
            PROJECT_HAS_JS = "On"
            PROJECT_HAS_CSS = "On"
            PROJECT_HAS_COMPOSER = "On"
            PROJECT_HAS_NPM = "On"
            PROJECT_HAS_DOCKER = "Off"
            ANALYZE_OWASP_DEPENDENCY_CHECKER = "Off"
            DOCS_PHP_DOCUMENTOR = "On"
            TEST_PHP_SPEC = "Off"
            TEST_CODECEPTION = "Off"
            TEST_PHP_BENCH = "Off"
            PUBLISH_TO_SATIS = "On"
        }
        options {
            ansiColor('xterm')
        }
        stages {
            stage('Setup parameters') {
                steps {
                    script {
                        properties([
                            parameters([
                                choice(
                                    choices: ['DEFAULT', 'FROM REMOTE REPOSITORY', 'FROM LOCAL DIRECTORY'],
                                    name: 'SOURCE'
                                )
                            ])
                        ])
                    }
                }
            }
            stage ('Get Source from Remote Repository')
            {
                when {
                    expression {
                       return params.SOURCE == 'FROM REMOTE REPOSITORY'
                    }
                }
                steps {
                        cleanWs()
                        checkout([
                                    $class: 'GitSCM',
                                    branches: [[name: "${env.REMOTE_BRANCH}"]],
                                    userRemoteConfigs: [
                                        [
                                            refspec: '+refs/tags/*:refs/remotes/origin/tags/*',
                                            url: "${env.REMOTE_REPOSITORY}"
                                        ]
                                    ]
                                ])
                    }
            }
            stage ('Get Source from Local Directory')
            {
                when {
                    expression {
                       return params.SOURCE == 'FROM LOCAL DIRECTORY'
                    }
                }
                steps
                {
                    cleanWs()
                    sh "cp -rT ${env.LOCAL_DIRECTORY} ."
                }
            }
            stage ('Set Up Environment')
            {
                steps
                {
                    sh 'whoami'
                    sh "update-alternatives --set php /usr/bin/php${env.PHP_VERSION}"
                }
            }
            stage ('Prepare filesystem')
            {
                steps
                {
                    sh 'mkdir dist published reports reports/lint reports/analyze reports/tests reports/docs'
                }
            }
            stage ('Lint')
            {
                parallel
                {
                    stage('Check Standard Files')
                    {
                        steps
                        {
                            script {
                                ppl_lint_standard()
                            }
                        }
                    }
                    stage('Lint Git')
                    {
                        steps
                        {
                            script {
                                ppl_lint_git()
                            }
                        }
                    }
                    stage('Lint PHP')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                        }
                        steps {
                            script {
                                ppl_lint_php()
                            }
                        }
                    }
                    stage('Lint JSON')
                    {
                        steps
                        {
                            script {
                                ppl_lint_json()
                            }
                        }
                    }
                    stage('Lint YAML')
                    {
                        steps {
                            script {
                                ppl_lint_yaml()
                            }
                        }
                    }
                    stage('Lint .env')
                    {
                        steps {
                            script {
                                ppl_lint_dotenv()
                            }
                        }
                    }
                    stage('Lint JS')
                    {
                        when {
                            environment name: 'PROJECT_HAS_JS', value: "On"
                        }
                        steps {
                            script {
                                ppl_lint_js()
                            }
                        }
                    }
                    stage('Lint Shell Scripts')
                    {
                        steps {
                            script {
                                ppl_lint_shellcheck()
                            }
                        }
                    }
                    stage('Lint Dockerfile')
                    {
                        when {
                            environment name: 'PROJECT_HAS_DOCKER', value: "On"
                        }
                        steps {
                             script {
                                ppl_lint_dockerfile()
                            }
                        }
                    }
                    stage('Lint Markdown')
                    {
                        steps {
                            script {
                                ppl_lint_markdown()
                            }
                        }
                    }
                    stage('Lint LESS')
                    {
                        when {
                            environment name: 'PROJECT_HAS_CSS', value: "On"
                        }
                        steps {
                            script {
                                ppl_lint_less()
                            }
                        }
                    }
                    stage('Lint EcmaScript')
                    {
                        when {
                            environment name: 'PROJECT_HAS_JS', value: "On"
                        }
                        steps {
                            script {
                                ppl_lint_es()
                            }
                        }
                    }
                    stage('Lint Stylesheets')
                    {
                        when {
                            environment name: 'PROJECT_HAS_CSS', value: "On"
                        }
                        steps {
                            script {
                                ppl_lint_style()
                            }
                        }
                    }
                    stage('Lint Text')
                    {
                        steps {
                            script {
                                ppl_lint_text()
                            }
                        }
                    }
                }
            }
            stage ('Dependencies')
            {
                parallel
                {
                    stage ('NPM')
                    {
                        stages
                        {
                            stage('Lint Package.json')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_NPM', value: "On"
                                }
                                steps {
                                    script {
                                        ppl_lint_npm()
                                    }
                                }
                            }
                            stage('Get NPM Dependencies')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_NPM', value: "On"
                                }
                                steps {
                                    script {
                                        ppl_npm_install()
                                    }
                                }
                            }
                            stage('Analyze NPM Outdated')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_NPM', value: "On"
                                }
                                steps {
                                    script {
                                        ppl_npm_outdated()
                                    }
                                }
                            }
                            stage('Analyze NPM How Fat')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_NPM', value: "On"
                                }
                                steps {
                                    script {
                                        ppl_npm_howfat()
                                    }
                                }
                            }
                        }
                    }
                    stage ('Composer')
                    {
                        stages
                        {
                            stage('Validate Composer')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_COMPOSER', value: "On"
                                }
                                steps {
                                    script {
                                        ppl_composer_validate()
                                    }
                                }
                            }
                            stage('Install Composer Dependencies')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_COMPOSER', value: "On"
                                }
                                steps {
                                    script {
                                        ppl_composer_install()
                                    }
                                }
                            }
                            stage('Analyze Composer Unused')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_COMPOSER', value: "On"
                                }
                                steps {
                                    script {
                                        ppl_composer_unused()
                                    }
                                }
                            }
                            stage('Analyze Composer Require Checker')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_COMPOSER', value: "On"
                                }
                                steps {
                                    script {
                                        ppl_composer_require_checker()
                                    }
                                }
                            }
                            stage('Analyze Composer Outdated')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_COMPOSER', value: "On"
                                }
                                steps {
                                    script {
                                        ppl_composer_outdated()
                                    }
                                }
                            }
                            stage('Analyze Composer Licenses')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_COMPOSER', value: "On"
                                }
                                steps {
                                    script {
                                        ppl_composer_licenses()
                                    }
                                }
                            }
                            stage('Generate Composer Graph')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_PHP', value: "On"
                                    environment name: 'PROJECT_HAS_COMPOSER', value: "On"
                                }
                                steps {
                                    script {
                                        ppl_composer_graph()
                                    }
                                    publishHTML target: [
                                        allowMissing: false,
                                        alwaysLinkToLastBuild: false,
                                        keepAll: true,
                                        reportDir: './reports/docs/',
                                        reportFiles: 'composer-graph.html',
                                        reportName: 'Composer Graph Report'
                                      ]
                                }
                            }
                        }
                    }
                }
            }

            stage('Analyze')
            {
                parallel
                {
                    stage('Datamine Repository')
                    {
                        steps {
                            mineRepository()
                        }
                    }
                    stage('Collect PHPLOC Metrics')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                        }
                        steps {
                            script
                            {
                                ppl_analyze_phploc()
                            }
                        }
                    }
                    stage('Check PHP for Copy-Paste')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                        }
                        steps {
                            script {
                                ppl_analyze_phpcpd()
                            }
                        }
                    }
                    stage('Check for Magic Numbers')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                        }
                        steps {
                            script {
                                ppl_analyze_phpmnd()
                            }
                        }
                    }
                    stage('Check PHP Code Style')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                        }
                        steps {
                            script {
                                ppl_analyze_phpcs()
                            }
                        }
                    }
                    stage('Analyze PHP with Psalm')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                        }
                        steps {
                            script {
                                ppl_analyze_psalm()
                            }
                        }
                    }
                    stage('Analyze PHP with PHPStan')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                        }
                        steps {
                            script {
                                ppl_analyze_phpstan()
                            }
                        }
                    }
                    stage('Analyze PHP with Phan')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                        }
                        steps {
                            script {
                                ppl_analyze_phan()
                            }
                        }
                    }
                    stage('Analyze PHP with PDepend')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                        }
                        steps {
                            script
                            {
                                ppl_analyze_pdepend ()
                            }
                        }
                    }
                    stage('Analyze PHP with PHPMD')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                        }
                        steps {
                            script {
                                ppl_analyze_phpmd ()
                            }
                        }
                    }
                    stage('Collect Dephpend Metrics')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                        }
                        steps {
                            script {
                                ppl_analyze_dephpend()
                            }
                        }
                    }
                    stage('Collect TODOs / FIXMEs')
                    {
                        steps {
                            script {
                                ppl_analyze_todo()
                            }
                        }
                    }
                    stage('OWASP Dependency Check')
                    {
                        when {
                            environment name: 'ANALYZE_OWASP_DEPENDENCY_CHECKER', value: "On"
                        }
                        steps {
                            script {
                                ppl_analyze_owasp_dependency_check()
                            }
                        }
                    }
                }
            }
            stage ('Test')
            {
                parallel
                {
                    stage('Run PHPSpec')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                            environment name: 'TEST_PHP_SPEC', value: "On"
                        }
                        steps
                        {
                            script {
                                ppl_test_php_spec()
                            }
                        }
                    }
                    stage('Run Codeception')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                            environment name: 'TEST_CODECEPTION', value: "On"
                        }
                        steps
                        {
                            script {
                                ppl_test_codeception()
                            }
                        }
                    }
                    stage('Run Infection')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                            environment name: 'TEST_INFECTION', value: "On"
                        }
                        steps
                        {
                            script {
                                ppl_test_infection()
                            }
                        }
                    }

                    stage('Run PHPBench')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                            environment name: 'TEST_PHP_BENCH', value: "On"
                        }
                        steps
                        {
                            script {
                                ppl_test_php_bench()
                            }
                        }
                    }
                }
            }
            stage ('Generate Documentation')
            {
                parallel
                {
                    stage('Generate phpDocumentor')
                    {
                        when {
                            environment name: 'PROJECT_HAS_PHP', value: "On"
                            environment name: 'DOCS_PHP_DOCUMENTOR', value: "On"
                        }
                        steps {
                            script {
                                ppl_docs_phpdoc()
                            }
                        }
                    }
                }
            }
            stage('Build')
            {
                stages
                {
                    stage ('Determine Version')
                    {
                        steps
                        {
                            script
                            {
                                version = ppl_determine_version()
                                currentBuild.displayName = version
                            }
                        }
                    }
                    stage ('Stamp Version to Composer.json')
                    {
                        when {
                            environment name: 'PROJECT_HAS_COMPOSER', value: "On"
                        }
                        steps
                        {
                            sh "/tools/stamp-version-composer ${version}"
                        }
                    }
                    stage ('Stamp Version to Package.json')
                    {
                        when {
                            environment name: 'PROJECT_HAS_NPM', value: "On"
                        }
                        steps
                        {
                            sh "/tools/stamp-version-npm ${version}"
                        }
                    }
                    stage ('Generate Changelog')
                    {
                        steps
                        {
                            ppl_changelog_generate()
                        }
                    }
                    stage ('Write SDLC Rating')
                    {
                        steps
                        {
                            script
                            {
                                ppl_write_score()
                            }
                        }
                    }
                    stage ('Copy files')
                    {
                        parallel
                        {
                            stage ('Copy distribution files')
                            {
                                steps
                                {
                                    sh 'cp -r ${DISTRIBUTION_FILES} dist/'
                                }
                            }
                            stage ('Copy Composer-related files')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_COMPOSER', value: "On"
                                }
                                steps
                                {
                                   sh 'cp composer.json composer.lock dist/'
                                }
                            }
                            stage ('Copy NPM-related files')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_NPM', value: "On"
                                }
                                steps
                                {
                                   sh 'cp package.json package-lock.json dist/'
                                }
                            }
                            stage ('Copy Docker-related files')
                            {
                                when {
                                    environment name: 'PROJECT_HAS_DOCKER', value: "On"
                                }
                                steps
                                {
                                    sh 'cp Dockerfile docker-compose.* dist/'
                                }
                            }
                        }
                    }
                    stage ('Pack Tarball')
                    {
                        steps
                        {
                            script
                            {
                                ppl_pack_tarball()
                            }
                        }
                    }
                    stage ('Pack PHAR')
                    {
                        steps
                        {
                            script
                            {
                                ppl_pack_phar ()
                            }
                        }
                    }
                }
            }
            stage('Publish')
            {
                parallel
                {
                    stage ('Publish to Satis')
                    {
                        when {
                            environment name: 'PROJECT_HAS_COMPOSER', value: "On"
                            environment name: 'PUBLISH_TO_SATIS', value: "On"
                        }
                        steps
                        {
                            script
                            {
                                ppl_publish_satis ()
                            }
                        }
                    }
                    stage ('Publish to Artifactory')
                    {
                        when {
                            environment name: 'PROJECT_HAS_NPM', value: "On"
                        }
                        steps
                        {
                            script
                            {
                                ppl_publish_npm ()
                            }
                        }
                    }
                    stage ('Generate Docker Image')
                    {
                        when {
                            environment name: 'PROJECT_HAS_DOCKER', value: "On"
                        }
                        steps
                        {
                            script
                            {
                                ppl_docker_build()
                            }
                        }
                    }
                }
            }
        }
        post {
            always {
                recordIssues enabledForFailure: true, tool: checkStyle(pattern: '**/reports/*/*.checkstyle.xml')
                archiveArtifacts artifacts: 'reports/**', followSymlinks: false
                /*
                xunit (
                    thresholds: [ skipped(failureThreshold: '0'), failed(failureThreshold: '0') ],
                    tools: [
                        PHPUnit(pattern: 'reports/tests/*-phpunit.xml')]
                )
                xunit (
                    thresholds: [ skipped(failureThreshold: '0'), failed(failureThreshold: '0') ],
                    tools: [
                        JUnit(pattern: 'reports/tests/*-junit.xml')]
                )*/
            }
            success
            {
                archiveArtifacts artifacts: 'published/**', followSymlinks: false
            }
            unstable
            {
                archiveArtifacts artifacts: 'published/**', followSymlinks: false
            }
        }
    }

    def ppl_lint_standard ()
    {
        standardFiles = [
                            'CODEOWNERS',
                            'README.md',
                            'LICENSE.md',
                            'CONTRIBUTING.md',
                            'SECURITY.md',
                            '.editorconfig',
                            '.gitignore',
                            '.dockerignore',
                            '.markdownlint.yaml',
                            '.markdownlintignore',
                            '.textlintrc',
                            '.yamllint',
                            'Dockerfile',
                            'docker-compose.yaml',
                            'example.env'
                        ]

        standardFiles.each { item ->
            if (fileExists(item)) {
                sh "echo '${item} does exists' >> reports/GOOD"
            }
            else
            {
                sh "echo '${item} does not exists' >> reports/BAD"
                fu_score+=5;
            }
        }
    }

    def ppl_lint_git()
    {
        def logPath = './reports/lint/git-branches.list'
        try {
            sh "git-list-branches > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=25;
        }
    }

    def ppl_lint_php()
    {
        def logPath = './reports/lint/php.checkstyle.xml'
        try {
            sh "parallel-lint -j \$(nproc) --exclude .git --exclude vendor --checkstyle src > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=10;
        }
    }

    def ppl_lint_json()
    {
        def logPath = './reports/lint/json.log'
        try {
            sh "find src -name '*.json' | parallel -j\$(nproc) jsonlint -qc >> ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_lint_yaml()
    {
        def logPath = './reports/lint/yaml.log'
        try {
            sh "yamllint src/ *.yml | tee > ${logPath}"
            // TODO Checkstyle
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_lint_dotenv()
    {
        def logPath = './reports/lint/dotenv.log'
        try {
            sh "dotenv-linter . > ${logPath}"
            // TODO Checkstyle
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_lint_js()
    {
        def logPath = './reports/lint/jshint.checkstyle.xml'
        try {
            sh "jshint --reporter=checkstyle src > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=10;
        }
    }

    def ppl_lint_shellcheck()
    {
        def logPath = './reports/lint/shellcheck.checkstyle.xml'
        try {
            sh "shellcheck -f checkstyle bin/* > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=10;
        }
    }

    def ppl_lint_dockerfile()
    {
        def logPath = './reports/lint/dockerfile.log'
        try {
            sh "dockerfilelint Dockerfile > ${logPath}"
            // TODO Checkstyle
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_lint_markdown()
    {
        def logPath = './reports/lint/markdown.log'
        try {
            sh "markdownlint --dot \"**/*.md\" | tee > ${logPath}"
            // TODO Checkstyle
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_lint_less()
    {
        def logPath = './reports/lint/less.checkstyle.xml'
        try {
            sh "lesshint -r lesshint-reporter-checkstyle src > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=5;
        }
    }

    def ppl_lint_es()
    {
        def logPath = './reports/lint/es.checkstyle.xml'
        try {
            sh "eslint --max-warnings 0 --format checkstyle src -o ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=5;
        }
    }

    def ppl_lint_style()
    {
        def logPath = './reports/lint/style.checkstyle.xml'
        try {
            sh "stylelint --mw 0 './src/**/*.css' --custom-formatter /usr/lib/node_modules/stylelint-checkstyle-formatter/index.js > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=5;
        }
    }

    def ppl_lint_text()
    {
        def logPath = './reports/lint/textlint.checkstyle.xml'
        try {
            sh "textlint --experimental --parallel --max-concurrency \$(nproc) --format checkstyle './*.md' > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=5;
        }
    }

    def ppl_lint_npm()
    {
        def logPath = './reports/lint/package-json.log'
        try {
            sh "npmPkgJsonLint . > ${logPath}"
            // TODO Checkstyle
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_npm_install()
    {
        def logPath = './reports/analyze/npm-install.log'
        try {
            sh "npm install --no-dev > ${logPath}"
        }
        catch (err) {
            failed(message: "${STAGE_NAME} is failed")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_npm_outdated()
    {
        def logPath = './reports/analyze/npm-outdated.log'
        try {
            sh "npm outdated > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_npm_howfat()
    {
        def logPath = './reports/analyze/npm-howfat.log'
        try {
            sh "npx howfat > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_composer_validate()
    {
        def logPath = './reports/lint/composer.log'
        try {
            sh "composer validate --no-check-all --strict > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_composer_install()
    {
        def logPath = './reports/analyze/composer-install.log'
        try {
            sh "composer install --no-progress --optimize-autoloader  > ${logPath}"
        }
        catch (err) {
            failed(message: "${STAGE_NAME} is failed")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_composer_unused()
    {
        def logPath = './reports/analyze/composer-unused.log'
        try {
            sh "composer-unused > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_composer_require_checker()
    {
        def logPath = './reports/analyze/composer-require-checker.log'
        try {
            sh "composer-require-checker check > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_composer_outdated()
    {
        def logPath = './reports/analyze/composer-outdated.log'
        try {
            sh "composer outdated -m > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_composer_licenses()
    {
        def logPath = './reports/analyze/composer-licenses.log'
        try {
            sh "composer licenses > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_composer_graph()
    {
        def logPath = './reports/docs/composer-graph.html'
        try {
            sh "composer-graph build -elPpd --output=${logPath} --quiet --ansi --no-interaction"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=15;
        }
    }

    def ppl_analyze_phploc()
    {
        try {
            sh 'phploc --log-xml=reports/analyze/phploc-self.xml --log-csv=reports/analyze/phploc-self.csv src/ > ./reports/analyze/phploc-self.log'
            sh 'phploc --log-xml=reports/analyze/phploc-vendor.xml --log-csv=reports/analyze/phploc-vendor.csv vendor/ > ./reports/analyze/phploc-vendor.log'
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=15;
        }
    }

    def ppl_analyze_phpcpd()
    {
        def logPath = './reports/analyze/phpcpd.log'
        try {
            sh "phpcpd --fuzzy src/ > ${logPath}"
            // TODO Checkstyle
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=10;
        }
    }

    def ppl_analyze_phpmnd()
    {
        def logPath = './reports/analyze/phpmnd.log'
        try {
            sh "phpmnd src --hint --ignore-numbers=2,-1 --extensions=default_parameter,-return,argument > ${logPath}"
            // TODO Checkstyle
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=10;
        }
    }

    def ppl_analyze_phpcs()
    {
        def logPath = './reports/analyze/phpcs.checkstyle.xml'
        try {
            sh "phpcs --report=checkstyle --extensions=php --standard=PSR12 --parallel=\$(nproc) src/ > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=10;
        }
    }

    def ppl_analyze_psalm()
    {
        def logPath = './reports/analyze/psalm.checkstyle.xml'
        try {
            sh "psalm --init"
            sh "psalm --output-format=checkstyle >> ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=5;
        }
    }

    def ppl_analyze_phpstan()
    {
        def logPath = './reports/analyze/phpstan.checkstyle.xml'
        try {
            sh "phpstan analyse --level 6 --no-progress --error-format=checkstyle src > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=5;
        }
    }

    def ppl_analyze_phan()
    {
        def logPath = './reports/analyze/phan.checkstyle.xml'
        try {
            sh "phan --init --init-level=1 > /dev/null"
            sh "phan --analyze-twice --no-progress-bar --target-php-version=${PHP_VERSION} -m checkstyle --output ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=5;
        }
    }

    def ppl_analyze_dephpend()
    {
        def logPath = './reports/analyze/dephpend-metrics.log'
        try {
            sh "dephpend metrics src > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_analyze_pdepend ()
    {
        def logPath = './reports/analyze/pdepend.log'
        try {
            sh "pdepend --summary-xml=reports/analyze/pdepend.xml --jdepend-chart=reports/analyze/jdepend.svg --overview-pyramid=reports/analyze/pyramid.svg src > ${logPath}"
            // TODO JDepend
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_analyze_phpmd ()
    {
        def logPath = './reports/analyze/phpmd.log'
        try {
            sh "phpmd src text cleancode,codesize,controversial,design,naming,unusedcode > ${logPath}"
            // TODO Checkstyle
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_analyze_todo ()
    {
        def logPath = './TODO.md'
        try {
            sh "leasot --reporter=markdown --skip-unsupported --exit-nicely src  > ${logPath}"
            // TODO Checkstyle
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=5;
        }
    }

    def ppl_analyze_owasp_dependency_check ()
    {
        def logPath = 'reports/tests/dependency-check-junit.xml'
        try {
            sh "/tools/dependency-check/bin/dependency-check.sh --project=${env.JOB_NAME} --format=JUNIT --out=${logPath} --scan ."
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=5;
        }
    }

    def ppl_test_php_spect ()
    {
        def logPath = './reports/tests/phpspec-junit.xml'
        try {
            sh "phpspec run --format=junit > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=50;
        }
    }

    def ppl_test_php_codeception ()
    {
        def logPath = './reports/tests/php.codeception.log'
        try
        {
            sh "codecept run --no-colors --xml > ${logPath}"
            sh "mv tests/_output/report.xml reports/tests/codeception-phpunit.xml"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=50;
        }
    }

    def ppl_test_php_infection ()
    {
        def logPath = './reports/tests/php.infection.log'
        try {
            sh "./vendor/bin/infection --threads=\$(nproc) --min-msi=70 --only-covered --log-verbosity=all --no-progress > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=25;
        }
    }

    def ppl_test_php_bench ()
    {
        def logPath = './reports/tests/php.bench.log'
        try {
            sh "phpbench run benchmarks --report=overview > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=25;
        }
    }

    def ppl_docs_phpdoc ()
    {
        def logPath = './reports/docs/phpDocumentor.log'
        try {
            sh "/tools/phpDocumentor -d src -t ./docs/generated > ${logPath}"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            sh "cat ${logPath} >> reports/BAD"
            fu_score+=15;
        }
    }

    def ppl_determine_version ()
    {
        def version = sh(returnStdout: true, script: 'git describe --tags --abbrev=0').trim()
        version += ".${env.BUILD_NUMBER}"

        return version
    }

    def ppl_changelog_generate ()
    {
        echo "TODO"
    }

    def ppl_docker_build ()
    {
        try {
            docker.withRegistry("${env.DOCKER_REGISTRY_HOST}:${env.DOCKER_REGISTRY_PORT}") {
                dockerImage = docker.build("${env.JOB_NAME}:${version}")
                dockerImage.push();
                dockerImage.push('latest');
            }
            sh "docker save -o ./published/${env.JOB_NAME}-${version}.docker.tar ${env.JOB_NAME}:${version}"
            sh "pigz ./published/${env.JOB_NAME}-${version}.docker.tar"
        }
        catch (err) {
            unstable(message: "${STAGE_NAME} is unstable")
            fu_score+=50;
        }
    }

    def ppl_pack_tarball ()
    {
        sh "tar czf ./published/${env.JOB_NAME}-${version}.tar.gz dist"
    }

    def ppl_pack_phar ()
    {
        sh "/tools/make-phar"
        sh "mv ${env.JOB_NAME}.phar ./published/${env.JOB_NAME}-${version}.phar"
    }

    def ppl_publish_satis ()
    {
        sh "cp published/${env.JOB_NAME}-${version}.tar.gz /satis/artifacts/"
    }

    def ppl_publish_npm ()
    {
        echo "TODO"
    }

    def ppl_write_score()
    {
        sh "echo ${fu_score} > ./reports/sdlc.log"
        currentBuild.description = "FU Score: ${fu_score}";
    }
