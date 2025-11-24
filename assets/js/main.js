document.querySelectorAll('.has-child .click').forEach(item => {
    item.addEventListener('click', function (e) {
        e.stopPropagation(); // không cho event lan ra ngoài

        const parentLi = this.closest('.has-child');

        parentLi.classList.toggle('open');
    });
});

// Trang save job click
document.querySelectorAll('.has-child-one .clickd').forEach(item => {
    item.addEventListener('click', function (e) {
        e.stopPropagation();

        const parentLi = this.closest('.has-child-one');
        parentLi.classList.toggle('opend');
    });
});

console.log(document.querySelectorAll('.has-child-one'));
console.log(document.querySelectorAll('.has-child-one .clickd'));


