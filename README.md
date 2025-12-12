# Real-Time Notification System (Laravel + Redis + Soketi)

This project is a real-time notification system built using **Laravel**, **Redis**, **Soketi**, and **Laravel Echo**.  
It demonstrates event broadcasting, WebSocket communication, and listening for notifications in real time.

---

## Features

- Real-time notifications using WebSockets  
- Event Broadcasting with Laravel  
- Redis as broadcasting/queue driver  
- Soketi as WebSocket Server  
- Laravel Echo + Pusher JS client  
- Example event + frontend listener  
- Clean and easy-to-understand structure  

---

## Tech Stack

- **Laravel** – Backend framework  
- **Redis** – Broadcasting / Queue  
- **Soketi** – WebSocket server (Pusher protocol compatible)  
- **Laravel Echo** – Frontend event listener  
- **Pusher JS** – WebSocket client library  

---

## Installation Guide

### Clone the repository

## Install PHP dependencies
composer install


## Install Node dependencies
npm install


## Create environment file
cp .env.example .env

## Environment Configuration
- BROADCAST_DRIVER=pusher

- PUSHER_APP_ID=local-app-id
- PUSHER_APP_KEY=local-app-key
- PUSHER_APP_SECRET=local-app-secret
- PUSHER_APP_CLUSTER=mt1

- PUSHER_HOST=127.0.0.1
- PUSHER_PORT=6001
- PUSHER_SCHEME=http
- PUSHER_ENCRYPTED=false

- MIX_PUSHER_APP_KEY=${PUSHER_APP_KEY}
- MIX_PUSHER_HOST=${PUSHER_HOST}
- MIX_PUSHER_PORT=${PUSHER_PORT}
- MIX_PUSHER_SCHEME=${PUSHER_SCHEME}


## Start Required Services

- **Start Redis** - redis-server
- **Start Soketi WebSocket Server** - soketi start
- **Start Laravel Application** - php artisan serve
- **Start Frontend Build** - npm run dev



**Server running on** -  http://127.0.0.1:8000

