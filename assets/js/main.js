// Import necessary libraries
import * as THREE from 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.module.js';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

// Initialize interactive globe
class GlobeAnimation {
    constructor() {
        this.scene = new THREE.Scene();
        this.camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        
        this.init();
        this.animate();
    }

    init() {
        // Setup renderer
        this.renderer.setSize(500, 500);
        document.querySelector('.globe-animation').appendChild(this.renderer.domElement);

        // Create globe geometry
        const geometry = new THREE.SphereGeometry(2, 32, 32);
        const material = new THREE.MeshPhongMaterial({
            map: new THREE.TextureLoader().load('/assets/images/earth-texture.jpg'),
            bumpMap: new THREE.TextureLoader().load('/assets/images/earth-bump.jpg'),
            bumpScale: 0.05,
            specularMap: new THREE.TextureLoader().load('/assets/images/earth-specular.jpg'),
            specular: new THREE.Color('grey')
        });

        this.globe = new THREE.Mesh(geometry, material);
        this.scene.add(this.globe);

        // Add lights
        const light = new THREE.DirectionalLight(0xffffff, 1);
        light.position.set(5, 3, 5);
        this.scene.add(light);

        const ambient = new THREE.AmbientLight(0x333333);
        this.scene.add(ambient);

        this.camera.position.z = 5;
    }

    animate() {
        requestAnimationFrame(() => this.animate());
        this.globe.rotation.y += 0.005;
        this.renderer.render(this.scene, this.camera);
    }
}

// Initialize globe on page load
document.addEventListener('DOMContentLoaded', () => {
    new GlobeAnimation();
    initializeScrollAnimations();
    initializePartnersMap();
});

// Smooth scroll animations
const initializeScrollAnimations = () => {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
};

// Interactive partners map
const initializePartnersMap = () => {
    const map = L.map('world-map').setView([20, 0], 2);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add partner locations
    fetch('/api/partners.php')
        .then(response => response.json())
        .then(partners => {
            partners.forEach(partner => {
                L.marker([partner.lat, partner.lng])
                    .bindPopup(`
                        <strong>${partner.name}</strong><br>
                        ${partner.location}<br>
                        <a href="${partner.url}">Learn More</a>
                    `)
                    .addTo(map);
            });
        });
};

// Handle smooth scrolling for navigation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Initialize mobile navigation
const initMobileNav = () => {
    const menuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');

    menuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
        menuButton.classList.toggle('open');
    });
};

// Initialize all interactive elements
window.addEventListener('load', () => {
    initMobileNav();
    initializeScrollAnimations();
});