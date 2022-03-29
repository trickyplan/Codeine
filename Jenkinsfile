def fu_score = 0;
pipeline {
    agent {
        label 'sdlc-jenkins-agent'
    }
    environment {
        PATH = "${env.PATH}:${env.WORKSPACE}/vendor/bin:"
        PHP_VERSION = "8.0"
        DOCKER_REGISTRY_HOST = "http://localhost"
        DOCKER_REGISTRY_PORT = 5000
        REMOTE_REPOSITORY = "git@gitlab.trickyplan.com:codeine/codeine.git"
        REMOTE_BRANCH = "master"
        LOCAL_DIRECTORY = "/src/codeine"
        LINT_GIT = "On"
        ANALYZE_OWASP_DEPENDENCY_CHECKER = "Off"
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
                                choices: ['NATIVE', 'REMOTE', 'LOCAL'],
                                name: 'SOURCE',
                                description: "Remote — from Git, Local — from docker bind"
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
                   return params.SOURCE == 'REMOTE'
                }
            }
            steps {
                    cleanWs()
                    checkout([
                                $class: 'GitSCM',
                                branches: [[name: "${env.REMOTE_BRANCH}"]],
                                userRemoteConfigs: [
                                    [
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
                   return params.SOURCE == 'LOCAL'
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
                            standardFiles = [   'README.md',
                                                'LICENSE.md',
                                                'CONTRIBUTING.md',
                                                'CODEOWNERS',
                                                'SECURITY.md',
                                                '.editorconfig',
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
                    }
                }
                stage('Lint Git')
                {
                    when {
                        environment name: 'LINT_GIT', value: "On"
                    }
                    steps
                    {
                        script {
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
                        // TODO Check for stale branches
                    }
                }
                stage('Lint PHP')
                {
                    steps {
                        script {
                            def logPath = './reports/lint/php.checkstyle.xml'
                            try {
                                sh "parallel-lint -j \$(nproc) --exclude .git --exclude vendor --checkstyle src > ${logPath}"
                            }
                            catch (err) {
                                unstable(message: "${STAGE_NAME} is unstable")
                                fu_score+=10;
                            }
                        }
                    }
                }
                stage('Lint JSON')
                {
                    steps
                    {
                        script {
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
                    }
                }
                stage('Lint YAML')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Lint .env')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Lint JS')
                {
                    steps {
                        script {
                            def logPath = './reports/lint/jshint.checkstyle.xml'
                            try {
                                sh "jshint --reporter=checkstyle src > ${logPath}"
                            }
                            catch (err) {
                                unstable(message: "${STAGE_NAME} is unstable")
                                fu_score+=10;
                            }
                        }
                    }
                }
                stage('Lint Package.json')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Lint Shell Scripts')
                {
                    steps {
                        script {
                            def logPath = './reports/lint/shellcheck.checkstyle.xml'
                            try {
                                sh "shellcheck -f checkstyle bin/* > ${logPath}"
                            }
                            catch (err) {
                                unstable(message: "${STAGE_NAME} is unstable")
                                fu_score+=10;
                            }
                        }
                    }
                }
                stage('Lint Dockerfile')
                {
                    steps {
                         script {
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
                    }
                }
                stage('Lint Markdown')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Lint LESS')
                {
                    steps {
                        script {
                            def logPath = './reports/lint/less.checkstyle.xml'
                            try {
                                sh "lesshint -r lesshint-reporter-checkstyle src > ${logPath}"
                            }
                            catch (err) {
                                unstable(message: "${STAGE_NAME} is unstable")
                                fu_score+=5;
                            }
                        }
                    }
                }
                stage('Lint EcmaScript')
                {
                    steps {
                        script {
                            def logPath = './reports/lint/es.checkstyle.xml'
                            try {
                                sh "eslint --max-warnings 0 --format checkstyle src -o ${logPath}"
                            }
                            catch (err) {
                                unstable(message: "${STAGE_NAME} is unstable")
                                fu_score+=5;
                            }
                        }
                    }
                }
                stage('Lint Styles')
                {
                    steps {
                        script {
                            def logPath = './reports/lint/style.checkstyle.xml'
                            try {
                                sh "stylelint --mw 0 './src/**/*.css' --custom-formatter /usr/lib/node_modules/stylelint-checkstyle-formatter/index.js > ${logPath}"
                            }
                            catch (err) {
                                unstable(message: "${STAGE_NAME} is unstable")
                                fu_score+=5;
                            }
                        }
                    }
                }
                stage('Lint Text')
                {
                    steps {
                        script {
                            def logPath = './reports/lint/textlint.checkstyle.xml'
                            try {
                                sh "textlint --experimental --parallel --max-concurrency \$(nproc) --format checkstyle './*.md' > ${logPath}"
                            }
                            catch (err) {
                                unstable(message: "${STAGE_NAME} is unstable")
                                fu_score+=5;
                            }
                        }
                    }
                }
            }
        }

        stage('Validate Composer')
        {
            steps {
                script {
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
            }
        }

        stage('Get Composer Dependencies')
        {
            steps {
                script {
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
            }
        }
        stage('Get NPM Dependencies')
        {
            steps {
                script {
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
                stage('Analyze Composer Unused')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Analyze Composer Require Checker')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Analyze Composer Outdated')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Analyze Composer Licenses')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Analyze NPM Outdated')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Analyze NPM How Fat')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Collect PHPLOC Metrics')
                {
                    steps {
                        sh 'phploc --log-xml=reports/analyze/phploc-self.xml --log-csv=reports/analyze/phploc-self.csv src/ > ./reports/analyze/phploc-self.log'
                        sh 'phploc --log-xml=reports/analyze/phploc-vendor.xml --log-csv=reports/analyze/phploc-vendor.csv vendor/ > ./reports/analyze/phploc-vendor.log'
                    }
                }
                stage('Check for Copy-Paste')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Check for Magic Numbers')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Check Code Style')
                {
                    steps {
                        script {
                            def logPath = './reports/analyze/phpcs.checkstyle.xml'
                            try {
                                sh "phpcs --report=checkstyle --extensions=php --standard=PSR12 --parallel=\$(nproc) src/ > ${logPath}"
                            }
                            catch (err) {
                                unstable(message: "${STAGE_NAME} is unstable")
                                fu_score+=10;
                            }
                        }
                    }
                }
                stage('Analyze with Psalm')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Analyze with PHPStan')
                {
                    steps {
                        script {
                            def logPath = './reports/analyze/phpstan.checkstyle.xml'
                            try {
                                sh "phpstan analyse --level 6 --no-progress --error-format=checkstyle src > ${logPath}"
                            }
                            catch (err) {
                                unstable(message: "${STAGE_NAME} is unstable")
                                fu_score+=5;
                            }
                        }
                    }
                }
                stage('Analyze with Phan')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Analyze with PDepend')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Analyze with PHPMD')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Collect Dephpend Metrics')
                {
                    steps {
                        script {
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
                    }
                }
                stage('Collect TODOs / FIXMEs')
                {
                    steps {
                        script {
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
                    }
                }
                stage('OWASP Dependency Check')
                {
                    when {
                        environment name: 'ANALYZE_OWASP_DEPENDENCY_CHECKER', value: "On"
                    }
                    steps {
                        script {
                            def logPath = 'reports/tests/dependency-check-junit.xml'
                            try {
                                sh "/tools/dependency-check/bin/dependency-check.sh --project=${env.JOB_NAME} --format=JUNIT --out=${logPath} --scan ."
                            }
                            catch (err) {
                                unstable(message: "${STAGE_NAME} is unstable")
                                fu_score+=5;
                            }
                        }
                    }
                }
            }
        }
        stage('Test')
        {
            stages
            {
                stage('Run PHPSpec')
                {
                    when {
                        environment name: 'TEST_PHPSPEC', value: "On"
                    }
                    steps
                    {
                        script {
                            def logPath = './reports/tests/phpspec-junit.xml'
                            try {
                                sh "phpspec run --format=junit > ${logPath}"
                            }
                            catch (err) {
                                unstable(message: "${STAGE_NAME} is unstable")
                                fu_score+=50;
                            }
                        }
                    }
                }
                stage('Run Codeception')
                {
                    when {
                        environment name: 'TEST_CODECEPTION', value: "On"
                    }
                    steps
                    {
                        script {
                            def logPath = './reports/tests/php.codeception.log'
                            try {
                                sh "codecept run --no-colors --xml > ${logPath}"
                                sh "mv tests/_output/report.xml reports/tests/codeception-phpunit.xml"
                            }
                            catch (err) {
                                unstable(message: "${STAGE_NAME} is unstable")
                                sh "cat ${logPath} >> reports/BAD"
                                fu_score+=50;
                            }
                        }
                    }
                }
                /* stage('Run Infection')
                {
                    steps
                    {
                        script {
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

                    }
                } */
                stage('Run PHPBench')
                {
                    when {
                        environment name: 'TEST_PHP_BENCH', value: "On"
                    }
                    steps
                    {
                        script {
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
                    steps {
                        script {
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
                    }
                }
                stage('Generate Composer Graph')
                {
                    steps {
                        script {
                            def logPath = './reports/docs/composer-graph.html'
                            try {
                                sh "composer-graph build -elPpd --output=${logPath} --quiet --ansi --no-interaction"
                            }
                            catch (err) {
                                unstable(message: "${STAGE_NAME} is unstable")
                                fu_score+=15;
                            }
                        }
                    }
                }
            }
        }
        stage('Build')
        {
            stages
            {
                stage ('Copy files')
                {
                    steps
                    {
                        sh 'cp -r src/ dist/'
                        sh 'cp -r docs/ dist/'
                        sh 'cp -r tests/ dist/'
                        sh 'cp *.md CODEOWNERS dist/'
                        sh 'cp example.env dist/'
                        sh 'cp composer.json package.json dist/'
                        sh 'cp composer.lock package-lock.json dist/'
                        sh 'cp Dockerfile docker-compose.* dist/'
                    }
                }
                stage ('Determine Version')
                {
                    steps
                    {
                        script
                        {
                            version = sh(returnStdout: true, script: '/tools/semver-calc').trim()
                        }
                    }
                }
                stage ('Generate Changelog')
                {
                    steps
                    {
                        echo 'TODO'
                    }
                }
                stage ('Write SDLC Rating')
                {
                    steps
                    {
                        sh "echo ${fu_score} > ./reports/sdlc.log"
                    }
                }
                stage ('Generate Docker Image')
                {
                    steps
                    {
                        script
                        {
                            docker.withRegistry("${env.DOCKER_REGISTRY_HOST}:${env.DOCKER_REGISTRY_PORT}") {
                                dockerImage = docker.build("${env.JOB_NAME}:${version}")
                                dockerImage.push();
                                dockerImage.push('latest');
                            }
                        }
                    }
                }
            }
        }
        stage('Publish')
        {
            parallel
            {
                stage ('Create Tarball')
                {
                    steps
                    {
                        sh "tar czf ./published/${env.JOB_NAME}-${version}.tar.gz ./dist"
                    }
                }
                stage ('Create PHAR Package')
                {
                    steps
                    {
                        sh '/tools/make-phar'
                        sh "mv ${env.JOB_NAME}.phar ./published/${env.JOB_NAME}-${version}.phar"
                    }
                }
                stage ('Create Composer Package')
                {
                    steps
                    {
                        echo 'TODO'
                    }
                }
            }
        }
    }
    post {
        success {
            archiveArtifacts artifacts: 'published/**', followSymlinks: false
        }
        always {
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
            recordIssues enabledForFailure: true, tool: checkStyle(pattern: '**/reports/*/*.checkstyle.xml')
        }
    }
}
