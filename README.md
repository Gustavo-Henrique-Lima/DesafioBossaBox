<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## VUTTR API (Very Useful Tools to Remember)

A API VUTTR é um simples repositório para gerenciar ferramentas com seus respectivos nomes, links, descrições e tags. Foi desenvolvida utilizando o framework Laravel e o banco de dados MySQL.

## Como Rodar o Projeto
### Pré-requisitos

- PHP 8.0 ou superior
- Composer 2.0 ou superior

### Passos

1. **Clonar o Repositório:**

   ```bash
   git clone git@github.com:Gustavo-Henrique-Lima/DesafioBossaBox.git

2. **Navegue até o diretório do projeto:**

    ```bash
    cd DesafioBossaBox

3. **Instale as dependências:**

    ```bash
    composer install
4. **Crie arquivo .env:**
    ```bash
    cp .env.example .env
5. **Atualize as variáveis de ambiente do arquivo .env:**  
    ### As variáveis necessárias para rodar o projeto estão nas linhas 12 a 17 do arquivo .env.
    ```bash
    DB_CONNECTION=seuSgbd
    DB_HOST=127.0.0.1
    DB_PORT=3306 (Porta padrão de alguns SGBDs, verifique o seu e veja se é necessário alterar algo)
    DB_DATABASE=suaBaseDados
    DB_USERNAME=seuUsuario
    DB_PASSWORD=senhaDoSeuUsuario    
    
4. **Inicie o servidor de desenvolvimento:**
    ```bash
   php artisan serve
    
### Agora o servidor está rodando na porta 8000
## Funcionalidades
   ### Tag

    Listar Tags:
      Endpoint para recuperar a lista completa de tags.

    Salvar Tag:
      Endpoint para inserir uma nova Tag no banco de dados.

    Deletar Tag:
      Endpoint para deletar uma Tag do banco de dados.
      
  ### Tool

    Listar Tools:
      Endpoint para recuperar a lista completa de tools.

    Salvar Tool:
      Endpoint para inserir uma nova Tool no banco de dados.

    Deletar Tool:
      Endpoint para deletar uma Tool do banco de dados.

  ### Login
      Realizar login:
        Endpoint para realizar login do usuário.
        
## Documentação

  O projeto inclui documentação detalhada para facilitar o entendimento e a interação com a aplicação.
  A seguir estão os recursos de documentação disponíveis.

  ### Swagger

   A API é documentada usando o Swagger, que fornece uma interface interativa para explorar os endpoints 
  da aplicação.
  ### Acesso ao Swagger:
  **Com o projeto rodando**
  
  O Swagger pode ser acessado através do link: [Swagger UI](http://localhost:8000/api/documentation#/).
  
  A interface do Swagger oferece uma visão interativa dos endpoints, permitindo testar as operações
  diretamente na documentação.

## Conteinerização 
### Para criar uma imagem do projeto  
 O projeto já conta com o arquivo Dockerfile necessário para criar uma imagem e em seguida rodar um container, para criar a imagem entre na pasta raiz do projeto, garante que o docker desktop esteja rodando na sua máquina e digite o seguinte comando:  
        
    docker build -t bossaboxchallenge:v1.0 .
### Container
 Com a imagem criada levante um container:
     
    docker run -p 8000:8000 bossaboxchallenge:v1.0
Agora o container está rodando e monitorando a porta 8000

## Testes 
    A aplicação conta com alguns testes unitários e de integração que são responsáveis por verificar 
    e validar as funcionalidades do sistema.
    
        
## License
Este projeto está licenciado sob a licença [MIT license](https://opensource.org/licenses/MIT).
