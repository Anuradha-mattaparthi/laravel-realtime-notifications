# Real-Time Notification System (Laravel + Pusher)

This project is a real-time notification system built using Laravel’s broadcasting features with Pusher as the WebSocket provider.
It demonstrates how to broadcast events from the backend and receive live notifications on the frontend using Laravel Echo.

---

## Features

- A simple real-time notification system built with Laravel and Pusher WebSockets.
- Messages are stored in the database and processed through Laravel’s queue system. 
- After processing, events are broadcast so clients receive updates instantly.
- A minimal frontend listens via Laravel Echo and displays messages in real time. 
- The sender_id is automatically obtained from the authenticated user using Laravel Breeze.
- A queued job processes each message in the background, sanitizing content and adding any required metadata.
- After processing, the job broadcasts a message.received event through Pusher so all connected clients receive the update instantly. 

---

## Tech Stack

- **Laravel** – Backend framework  
- **Laravel Queue** – Handles background job processing 
- **Pusher** – WebSocket broadcasting service 
- **Laravel Echo** – Frontend event listener  
- **Pusher JS** – WebSocket client library used by Echo

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

- PUSHER_APP_ID=your_app_id
- PUSHER_APP_KEY=your_app_key
- PUSHER_APP_SECRET=your_app_secret_key
- PUSHER_APP_CLUSTER=your_cluster

- PUSHER_HOST=
- PUSHER_PORT=
- PUSHER_SCHEME=https
- PUSHER_ENCRYPTED=true

- MIX_PUSHER_APP_KEY=${PUSHER_APP_KEY}
- VITE_PUSHER_APP_CLUSTER=${PUSHER_APP_CLUSTER}

## Pusher Setup Requirements
- **Backend** - composer require pusher/pusher-php-server
- **Frontend (JavaScript / Vite)** - npm install pusher-js laravel-echo


## Start Required Services

- **Start Laravel Application** - php artisan serve
- **Start Frontend Build** - npm run dev



**Server running on** -  http://127.0.0.1:8000

