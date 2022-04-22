@Library("SDLC@master") _
import SDLC.Jenkins

pipeline {
    agent {
        label 'sdlc-jenkins-agent'
    }
    environment {
        PATH = "${env.PATH}:${env.WORKSPACE}/vendor/bin:"
        PHP_VERSION = "8.1"
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
        stage ('Environment & Versions')
        {
            steps
            {
                initializeSdlc()
                selectPhpVersion()
                collectBuildVersions()
                determineVersion()
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
                        checkStandardFiles()
                    }
                }
                stage('Lint Git')
                {
                    steps
                    {
                        lintGit()
                    }
                }
                stage('Lint PHP')
                {
                    when {
                        environment name: 'PROJECT_HAS_PHP', value: "On"
                    }
                    steps {
                        lintPhp()
                    }
                }
                stage('Lint JSON')
                {
                    steps
                    {
                        lintJson()
                    }
                }
                stage('Lint YAML')
                {
                    steps {
                        lintYaml()
                    }
                }
                stage('Lint .env')
                {
                    steps {
                        lintDotEnv()
                    }
                }
                stage('Lint Shell Scripts')
                {
                    steps {
                        lintShellCheck()
                    }
                }
                stage('Lint Dockerfile')
                {
                    when {
                        environment name: 'PROJECT_HAS_DOCKER', value: "On"
                    }
                    steps {
                        lintDockerfile()
                    }
                }
                stage('Lint Markdown')
                {
                    steps {
                        lintMarkdown()
                    }
                }
                stage('Lint LESS')
                {
                    when {
                        environment name: 'PROJECT_HAS_CSS', value: "On"
                    }
                    steps {
                        lintLess()
                    }
                }
                stage('Lint with JSHint')
                {
                    when {
                        environment name: 'PROJECT_HAS_JS', value: "On"
                    }
                    steps {
                        lintWithJsHint()
                    }
                }
                stage('Lint with ESLint')
                {
                    when {
                        environment name: 'PROJECT_HAS_JS', value: "On"
                    }
                    steps {
                        lintWithEsLint()
                    }
                }
                stage('Lint Stylesheets')
                {
                    when {
                        environment name: 'PROJECT_HAS_CSS', value: "On"
                    }
                    steps {
                        lintWithStylelint()
                    }
                }
                stage('Lint Text')
                {
                    steps {
                        lintText()
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
                    when {
                        environment name: 'PROJECT_HAS_NPM', value: "On"
                    }
                    steps
                    {
                        runNpmPipeline()
                    }
                }
                stage ('Composer')
                {
                    when {
                        environment name: 'PROJECT_HAS_COMPOSER', value: "On"
                    }
                    steps {
                        runComposerPipeline()
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
                        analyzeWithPhpLoc('src')
                        analyzeWithPhpLoc('vendor')
                    }
                }
                stage('Check PHP for Copy-Paste')
                {
                    when {
                        environment name: 'PROJECT_HAS_PHP', value: "On"
                    }
                    steps {
                        analyzeWithPhpCpd('src')
                    }
                }
                stage('Check for Magic Numbers')
                {
                    when {
                        environment name: 'PROJECT_HAS_PHP', value: "On"
                    }
                    steps {
                        analyzeWithPhpMnd()
                    }
                }
                stage('Check PHP Code Style')
                {
                    when {
                        environment name: 'PROJECT_HAS_PHP', value: "On"
                    }
                    steps {
                        analyzeWithPhpCs()
                    }
                }
                stage('Analyze PHP with Psalm')
                {
                    when {
                        environment name: 'PROJECT_HAS_PHP', value: "On"
                    }
                    steps {
                        analyzeWithPsalm()
                    }
                }
                stage('Analyze PHP with PHPStan')
                {
                    when {
                        environment name: 'PROJECT_HAS_PHP', value: "On"
                    }
                    steps {
                        analyzeWithPhpStan()
                    }
                }
                stage('Analyze PHP with Phan')
                {
                    when {
                        environment name: 'PROJECT_HAS_PHP', value: "On"
                    }
                    steps {
                        analyzeWithPhan()
                    }
                }
                stage('Analyze PHP with PDepend')
                {
                    when {
                        environment name: 'PROJECT_HAS_PHP', value: "On"
                    }
                    steps {
                        analyzeWithPdepend()
                    }
                }
                stage('Analyze PHP with PHPMD')
                {
                    when {
                        environment name: 'PROJECT_HAS_PHP', value: "On"
                    }
                    steps {
                        analyzeWithPhpMd()
                    }
                }
                stage('Collect Dephpend Metrics')
                {
                    when {
                        environment name: 'PROJECT_HAS_PHP', value: "On"
                    }
                    steps {
                        analyzeWithDephpend()
                    }
                }
                stage('Collect TODOs / FIXMEs')
                {
                    steps {
                        collectTodos ()
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
                        generateWithPhpDocumentor()
                    }
                }
                stage ('Generate Changelog')
                {
                    steps
                    {
                        generateChangelog()
                    }
                }
            }
        }
        stage('Build')
        {
            steps
            {
                copyDistributionFiles()
                buildTarball()
                buildPharball()
            }
        }
        stage('Publish')
        {
            parallel
            {
                stage ('Publish to Local Satis')
                {
                    when {
                        environment name: 'PROJECT_HAS_COMPOSER', value: "On"
                        environment name: 'PUBLISH_TO_SATIS', value: "On"
                    }
                    steps
                    {
                        publishToSatisViaVolume()
                    }
                }
            }
        }
    }
    post {
        always {
            writeScore()
            writeBuildReport()
            recordIssues    enabledForFailure: true, tool: checkStyle(pattern: '**/reports/*/*.checkstyle.xml')
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
    }
}
