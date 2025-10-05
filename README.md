<div align="center">

```
███████╗██╗   ██╗██████╗  ██████╗
██╔════╝██║   ██║██╔══██╗██╔═══██╗
███████╗██║   ██║██████╔╝██║   ██║
╚════██║██║   ██║██╔═══╝ ██║   ██║
███████║╚██████╔╝██║     ╚██████╔╝
╚══════╝ ╚═════╝ ╚═╝      ╚═════╝
```

### **The Social Network for the Terminal**

*A raw, unfiltered space for hackers. No algorithms. No distractions. Pure signal.*

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/php-8.4%2B-777BB4.svg?logo=php)](https://php.net)
[![Laravel](https://img.shields.io/badge/laravel-12-FF2D20.svg?logo=laravel)](https://laravel.com)

---

> **⚠️ This is the API Backend**
> For the CLI client, head over to **[supo-dev/cli](https://github.com/supo-dev/cli)**

</div>

---

## 🔥 What is Supo?

Supo is a terminal-exclusive social network that brings back the raw energy of early Twitter—before algorithms decided what you should see. It's built by developers, for developers.

- **📝 Post directly from your terminal** — No web browsers required
- **⚡ Lightning-fast interactions** — Follow, like, and engage instantly
- **🎯 Pure chronological feed** — See what matters, when it happens
- **🔐 Built with modern security** — Laravel Sanctum authentication
- **🚀 Vim bindings for navigation** — Because your fingers never leave home row
- **💬 Code snippets as first-class citizens** — Share code like you share thoughts

This is the API backend that powers it all. RESTful, secure, and blazingly fast.

---

## 🏗️ Architecture

```
┌──────────────────────────────────────────────────────────┐
│                     Supo CLI                             │
│        (Terminal UI with Vim bindings & colors)          │
└────────────────────────┬─────────────────────────────────┘
                         │
                    HTTPS/JSON
                         │
┌────────────────────────▼─────────────────────────────────┐
│                  Supo API (This Repo)                    │
└──────────────────────────────────────────────────────────┘
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

<div align="center">

**Built with ❤️ by terminal enthusiasts**

*Because not everything needs a GUI*

</div>
