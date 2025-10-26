# 🎮 Hydra – Esports Tournament Platform

> A full-stack multi-platform system for hosting esports tournaments with integrated betting, donations, and real-time functionality.

---

## 🧩 Tech Stack
![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/Symfony-000000?logo=symfony&logoColor=white)
![JavaFX](https://img.shields.io/badge/JavaFX-E76F00?logo=java&logoColor=white)
![Codename One](https://img.shields.io/badge/Codename%20One-005C97?logo=java&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-D22128?logo=apache&logoColor=white)

---

## 📚 Table of Contents
1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Key Features](#key-features)
4. [Setup](#setup)
5. [Achievements](#achievements)
6. [Author](#author)

---

## Overview
Hydra is an esports tournament platform that unifies web, mobile, and desktop applications.  
It allows users to organize tournaments, manage teams, track matches, and monetize their events through donations and betting.

Developed as part of the GL academic project at **ESPRIT**, it showcases full-stack development, modularity, and multi-platform architecture.

---

## Architecture
- **Web Application:** Symfony (PHP) for tournament management and user dashboards  
- **Mobile Application:** Codename One (Java) for Android and iOS  
- **Desktop Application:** JavaFX for administration and moderation  
- **Database:** MySQL for persistent data storage  
- **Backend Hosting:** Apache web server  

---

## Key Features
- 🏆 Tournament creation and management  
- 👥 Team registration and ranking system  
- 💰 Secure donation and betting system  
- 📊 Real-time leaderboard and match tracking  
- 🔐 Role-based access for admins, players, and viewers  

---

## Setup
```bash
# Backend setup
cd backend
composer install
php bin/console doctrine:migrations:migrate
php bin/console server:run

# Mobile app
cd mobile
mvn package

# Desktop app
cd desktop
mvn javafx:run
