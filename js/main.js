document.addEventListener('DOMContentLoaded',function(){
    var myBody = document.querySelectorAll('body')[0];
    var pid = myBody.getAttribute('p-id');
    var myNav = document.querySelectorAll('.navigation')[0];
    var navItems = myNav.querySelectorAll('a');
    switch(pid){
        case 'top':
            navItems[0].classList.add('navigation--active');
        break;
        case 'newest':
            navItems[1].classList.add('navigation--active');
        break;
        case 'add':
            navItems[2].classList.add('navigation--active');
        break;
    }
    console.log(navItems);
});