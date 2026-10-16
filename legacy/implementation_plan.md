# Implementation Plan - LSC Website Rebuild

The goal is to transform the existing static/PHP website of Lusaka South University College (LSC) into a modern, dynamic, enterprise-ready web application using Laravel and Vue.js.

## Tech Stack
- **Backend**: Laravel 11.x (PHP)
- **Frontend**: Vue.js 3.x with Inertia.js (for a seamless SPA experience)
- **Styling**: Vanilla CSS (Modern CSS with custom properties/variables)
- **Icons**: Lucide-vue-next (Replacing emojis with professional tech icons)
- **State Management**: Vue Composition API
- **Animations**: CSS Transitions & Keyframes / Framer Motion equivalent for Vue

## Phase 1: Setup & Environment
- [ ] Initialize Laravel project (In Progress)
- [ ] Install Laravel Breeze with Vue/Inertia stack
- [ ] Setup database (MySQL/SQLite depending on user preference, defaulting to SQLite for portability if not specified)
- [ ] Install Lucide-vue-next for icons

## Phase 2: Core Architecture
- [ ] Define main routes (`/`, `/about`, `/programmes`, `/admissions`, `/campus-life`, `/news`, `/contact`)
- [ ] Create Controllers for each section
- [ ] Design the Master Layout (Navigation, Footer, Sidebar) using Vue components
- [ ] Implement a clean, modern design system in `index.css`

## Phase 3: Content Migration & Beautification
- [ ] Migrate content from the legacy `index.html` to the Vue Home component
- [ ] Migrate other pages (Programmes, Admissions, etc.)
- [ ] Replace all emojis with Lucide icons
- [ ] Enhance visuals with modern gradients, glassmorphism, and smooth animations
- [ ] Ensure full responsiveness (Mobile-first approach)

## Phase 4: Dynamic Features & Admin CMS
- [ ] Build a contact/enquiry form with validation and backend handling
- [ ] Implement a News/Events system that can be managed via an admin panel
- [ ] Set up a basic but secure Admin Dashboard for content management

## Phase 5: Polish & SEO
- [ ] Optimize images and assets
- [ ] Implement SEO best practices (Meta tags, semantic HTML)
- [ ] Final testing for UI/UX consistency
