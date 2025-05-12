# Projeto feito em laravel 
# Bookstore (English Below)

Olá, me chamo João Gabriel, Sou um dev junior e durante meu tempo de aprendizado no meu estagio, fiz este projeto usando Laravel (Foi um dos primeiros, tive ajuda dos dev seniores e tambem de tutoriais da internet).

Esse é um sistema de gerenciamento de livros. Permite aos usuarios visualizar , filtrar e adicionar livros ao carrinho, bem como gerenciar wishlist e ter recomendacões, alem de outras funcionalidades.

## Linguagens utilizadas

- PHP 8.x
- Laravel 10.x
- Bootstrap 5
- Breeze
- SqLite 
- JQuery
- AJAX
- Toastr.js
- SweetAlert
- TailWind
- Google Books (API)
- Tawk.to

## Iniciação

Clone os arquivos e execute os seguintes comandos:

````bash
git clone https://github.com/GabrielVasc14/Fantasy-Bookstore.git
cd Fantasy-Bookstore
composer install
cp .env.example .env
php artisan key:generate
````

## Configure seu .env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE= database/database.sqlite
DB_USERNAME= root
DB_PASSWORD=

## Faça as migrações

php artisan migrate --seed

## Inicie o Servidor

php artisan serve

## Usuarios de teste

Admin:
Email: admin@gmail.com
Senha: 123456

User comum:
Email: user@gmail.com
Senha: 123456


## Funcionalidades do Projeto

. Listagem de livros em formato card

. Filtros por: titulo, autor, preço minimo e maximo

. Wishlist com AJAX

. Carrinho de compras

. Integração com Google Books API (admin)

. Paginação com botão de mostrar mais

. 4 CRUDS para criação de Cupons, Livros (caso nao queria usar a API), Recompenças e Desafios (admin)

. Historico de compras

. Check de compras (apenas uma simulação onde ao colocar oque pede, ira apenas apertar um botão para "Pagar")

. Recomendações de livros por diversas formas

. Forma de comprar (e como admin adicionar) audioBook e Ebook

. Historico de cupons usados no perfil

. Chat ao vivo usando Tawk.to

. Avaliações e comentarios em livros

e mais.


## Scripts e extras

. Uso de toastr.js para notificações visualemnte melhores

. Requisições AJAX para Wishlist e filtro

. Validação e interações com SweetAlert


------------------------------------------------------------------------------------------------------------

# Bookstore (English)

# Bookstore - Laravel Project

Hi! My name is João Gabriel. I'm a junior developer and during my learning journey at my internship, I built this project using Laravel. It was one of my first full projects — I had help from senior developers and online tutorials.

This is a book management system that allows users to view, filter, and add books to the cart, as well as manage their wishlist, access recommendations, and more.

## Technologies Used

- PHP 8.x  
- Laravel 10.x  
- SQLite  
- Bootstrap 5  
- Laravel Breeze  
- Tailwind CSS  
- jQuery  
- AJAX  
- Toastr.js  
- SweetAlert  
- Google Books API  
- Tawk.to (Live Chat)

## Getting Started

Clone the repository and run the following commands:

````bash
git clone https://github.com/GabrielVasc14/Fantasy-Bookstore.git
cd Fantasy-Bookstore
composer install
cp .env.example .env
php artisan key:generate
````

## Setup your .env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE= database/database.sqlite
DB_USERNAME= root
DB_PASSWORD=

## Make the migrations

php artisan migrate --seed

# Start the server

php artisan serve

## Test users

Admin:
Email: admin@gmail.com
Password: 123456

Regular User:
Email: user@gmail.com
Password: 123456


## Features

. Book listing in card format

. Filtering by title, author, category, and price range

. Wishlist system using AJAX

. Shopping cart

. Google Books API integration (admin only)

. Pagination with "Show More" button
. 4 CRUDs: Coupons, Books (manual input), Rewards, and Challenges (admin only)

. Purchase history

. Checkout simulation

. Book recommendations through different criteria

. Ability to buy and manage audiobooks and eBooks

. User coupon history

. Live chat using Tawk.to

. Book reviews and comments


## Extras

. Visual notifications with Toastr.js

. AJAX requests for filtering and wishlist actions

. SweetAlert for enhanced interactivity and alerts

