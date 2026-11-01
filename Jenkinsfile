
pipeline {
    agent any

    options {
        timestamps()
        disableConcurrentBuilds()
    }

    stages {
        stage('Clean Workspace') {
            steps {
                deleteDir()
                echo 'Workspace cleaned.'
            }
        }

        stage('Checkout SCM') {
            steps {
                checkout scm
                echo 'Source code checked out.'
            }
        }

        stage('Secret Scan - Filesystem') {
            steps {
                sh '''
                    set -eu

                    echo "=== Gitleaks Filesystem Scan ==="

                    # Workspace Jenkins berada di dalam volume jenkins_home.
                    case "$WORKSPACE" in
                        /var/jenkins_home/*)
                            SCAN_PATH="${WORKSPACE#/var/jenkins_home}"
                            GITLEAKS_PATH="/jenkins_home${SCAN_PATH}"
                            ;;
                        *)
                            echo "ERROR: Workspace path tidak sesuai."
                            echo "Workspace: $WORKSPACE"
                            exit 2
                            ;;
                    esac

                    docker run --rm \
                        -v jenkins_home:/jenkins_home \
                        zricethezav/gitleaks:latest \
                        dir "$GITLEAKS_PATH" \
                        --report-path "$GITLEAKS_PATH/gitleaks-filesystem.json" \
                        --report-format json \
                        --redact \
                        --exit-code 1
                '''
            }
        }

        stage('SonarQube SAST') {
            steps {
                withSonarQubeEnv('SonarQube') {
                    sh '''
                        set -eu

                        echo "=== SonarQube SAST Scan ==="

                        docker run --rm \
                            --network sonarqube-net \
                            -v jenkins_home:/var/jenkins_home \
                            -w "$WORKSPACE" \
                            -e SONAR_HOST_URL="$SONAR_HOST_URL" \
                            -e SONAR_TOKEN="$SONAR_AUTH_TOKEN" \
                            sonarsource/sonar-scanner-cli
                    '''
                }
            }
        }

        stage('Quality Gate') {
            steps {
                timeout(time: 10, unit: 'MINUTES') {
                    waitForQualityGate abortPipeline: true
                }
            }
        }

        stage('Approval Gate') {
            steps {
                timeout(time: 30, unit: 'MINUTES') {
                    input(
                        message: 'SAST dan Quality Gate selesai. Apakah hasil analisis disetujui?',
                        ok: 'Approve',
                        submitterParameter: 'APPROVED_BY'
                    )
                }
            }
        }
    }

    post {
        success {
            echo 'Pipeline completed successfully.'
        }

        failure {
            echo 'Pipeline failed. Check the stage logs for details.'
        }

        aborted {
            echo 'Pipeline was aborted.'
        }

        always {
            echo 'Pipeline execution finished.'
        }
    }
}
