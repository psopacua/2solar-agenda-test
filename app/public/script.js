Vue.component('timeline', {
    template: '#timeline-template',
    props: ['items']
});

Vue.component('timeline-item', {
    template: '#timeline-item-template',
    props: ['item']
});

new Vue({
    el: '#app',
    data: {}
})