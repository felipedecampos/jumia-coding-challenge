
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
Vue.use(require('vue-resource'));

Vue.component('data-component', require('./components/DataComponent.vue'));
Vue.component('pagination', require('laravel-vue-pagination'));

const app = new Vue({
    el: '#app'
});
