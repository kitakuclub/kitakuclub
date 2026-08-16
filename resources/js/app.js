import '@tabler/core';
import $ from 'jquery';
import { Timer as easytimer } from 'easytimer.js'
import Cookies from 'js-cookie'

import 'swiper/css';
import { Swiper as swiperCarousel } from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

window.$ = window.jQuery = $;
window.easytimer = easytimer;
window.cookies = Cookies;

window.swiper = swiperCarousel;
window.navigation = Navigation;
window.pagination = Pagination;
