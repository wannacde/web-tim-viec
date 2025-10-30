import './bootstrap';
import Alpine from 'alpinejs';
import AOS from 'aos';
import 'aos/dist/aos.css';
import Swiper from 'swiper';
import 'swiper/css';

window.Alpine = Alpine;
window.AOS = AOS;
window.Swiper = Swiper;

// Initialize AOS
AOS.init({
    duration: 1000,
    once: true,
    offset: 100
});

Alpine.start();
