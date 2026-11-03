# Laravel Security Audit - DevOpsSec

## Overview

Repository ini digunakan untuk praktikum implementasi security scanning pada aplikasi Laravel menggunakan pendekatan DevSecOps.

Proses pemeriksaan keamanan diintegrasikan ke dalam Jenkins Pipeline dengan menggunakan Gitleaks untuk mendeteksi kemungkinan secret exposure dan SonarQube untuk melakukan Static Application Security Testing (SAST).

Pipeline dirancang agar pemeriksaan keamanan dapat dilakukan secara otomatis sebagai bagian dari proses Continuous Integration (CI).

## Environment

Praktikum dijalankan menggunakan dengan beberapa tools yang dikemas menggunakan Docker.

| Komponen | Keterangan |
|---|---|
| Application | Laravel |
| CI/CD Tool | Jenkins |
| SAST Tool | SonarQube |
| Secret Scanning Tool | Gitleaks |
| Container Runtime | Docker |
| Jenkins Container | `jenkins` |
| Jenkins Image | `jenkins-dockercli:lts` |
| SonarQube Container | `sonarqube` |
| SonarQube Scanner Image | `sonarsource/sonar-scanner-cli` |
| Docker Network | `sonarqube-net` |

### Environment Architecture

Jenkins dan SonarQube dijalankan sebagai container Docker dan dihubungkan melalui Docker network `sonarqube-net`.

Jenkins bertindak sebagai orchestrator yang menjalankan tahapan pipeline. SonarQube berfungsi sebagai server analisis source code, sedangkan SonarQube Scanner digunakan oleh pipeline untuk mengirimkan hasil analisis ke server SonarQube.

Gitleaks dijalankan pada pipeline Jenkins untuk melakukan pemeriksaan secret pada source code.

Secara umum, hubungan antar-komponen dapat digambarkan sebagai berikut:

```text
                 +----------------------+
                 |   Laravel Repository |
                 +----------+-----------+
                            |
                            v
                 +----------------------+
                 |        Jenkins       |
                 |    CI/CD Pipeline    |
                 +----------+-----------+
                            |
             +--------------+--------------+
             |                             |
             v                             v
    +------------------+         +------------------+
    |     Gitleaks     |         | SonarQube Scanner |
    |  Secret Scanning |         |       SAST       |
    +------------------+         +--------+---------+
                                          |
                                          v
                                 +------------------+
                                 |    SonarQube     |
                                 |  Analysis Server |
                                 +------------------+
                                 
Pipeline flow

+----------------------+
|    Clean Workspace   |
+----------+-----------+
           |
           v
+----------------------+
|     Checkout SCM     |
+----------+-----------+
           |
           v
+----------------------+
| Gitleaks Filesystem  |
|        Scan          |
+----------+-----------+
           |
           v
+----------------------+
| Gitleaks Git History |
|        Scan          |
+----------+-----------+
           |
           v
+----------------------+
|    SonarQube Scan    |
+----------+-----------+
           |
           v
+----------------------+
|      Quality Gate   |
+----------+-----------+
           |
           v
+----------------------+
|     Approval Gate    |
+----------------------+
