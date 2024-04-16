# 2nd-project-group_34
2nd-project-group_34 created by GitHub Classroom

## Membros do Grupo

- Pedro Rodrigues  (102432)
- Guilherme Lopes (103896)
- Gabriel Teixeira (107876)

Aqui em Baixo tem o Relatorio com todas as explicações e suas vulnerabilidades:

[Relatório](/analysis/report.pdf)





###  Checklist, com os 6 problemas mais graves que optamos por revolver:

* Verify that directory browsing is disabled unless deliberately desired. Additionally, applications should not allow discovery or disclosure of file or directory metadata, such as Thumbs.db, .DS_Store, .git or .svn folders

* Verify that cookie-based session tokens have the 'Secure' attribute set. 

* Verify that the Cross-Origin Resource Sharing (CORS) Access-Control-Allow-Origin header uses a strict allow list of trusted domains and subdomains to match against and does not support the "null" origin

* Verify that if the application is published under a domain name with other applications that set or use session cookies that might override or disclose the session cookies, set the path attribute in cookie-based session tokens using the most precise path possible. 

* Verify the application generates a new session token on user authentication.

* Verify the application only stores session tokens in the browser using secure methods such as appropriately secured cookies (see section 3.4) or HTML 5 session storage.

###  Recursos usado 
* Password strength evaluation
  
* Google reCAPTCHA


## Razão porque escolhemos esses problemas:

- A principal  razão que o levou a escolher os 6 problemas mais graves que optamos por revolver foi a sua relevância crítica para a integridade e eficiência do nosso sistemas.

- Esse seleção baseou-se  no impacto potencial na segurança operacional, a vulnerabilidade aos ataques cibernéticos. Usamos uma abordagem que visa não apenas remediar falhas imediatas, mas também fortalecer nossa postura de segurança a longo prazo, assegurando a proteção de dados e a confiança dos nossos clientes.


## Como executar o nosso projeto


+ instalar o [XAMPP](https://www.apachefriends.org/download.html)
+ copiar a pasta app_sec para a pasta 'htdocs'
+ alterar os seguintes ficheiros
    - 'apache/conf/httpd.conf'
    - 'apache/conf/extra/httpd-vhosts.conf'
    - 'apache/makecert.bat'
    - 'apache/v3.ext'
+ executar 'makecert.bat' e criar um certificado com domain localhost
+ instalar o certificado 'apache/conf/ssl.crt/server.crt' (no processo de instalação guardar o certificado em Trusted Root Certification Authorities)
+ No phpMyAdmin criar uma database chamada 'sio' e importar o ficheiro sio.sql para a database
