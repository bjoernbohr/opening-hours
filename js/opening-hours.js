document.addEventListener('DOMContentLoaded', function () {

    let daysEnabled = document.getElementsByClassName('oh-days--enabled');
    let daysContainer = document.getElementById('oh-container');
    let dayContent = document.getElementsByClassName('oh-content--main');
    let current = document.getElementsByClassName('oh-current');

    daysEnabled[0].addEventListener('mouseenter', function (e) {
        daysContainer.classList.add('shown');
        daysContainer.classList.remove('hidden');
        current[0].classList.add('active');

        for (var i = 0; i < dayContent.length; i++) {
            if(dayContent != undefined) {
                (function(index) {
                    setTimeout(function() {
                        dayContent[index].classList.add('textAnimateIn');
                        dayContent[index].classList.remove('textAnimateOut');
                    }, i * 125);
                })(i);
            } else {
                return false;
            }
        }

    });

    daysContainer.addEventListener('mouseleave', function (e) {
        daysContainer.classList.remove('shown');
        daysContainer.classList.add('hidden');
        current[0].classList.remove('active');

        for (var i = 0; i < dayContent.length; i++) {
            if(dayContent != undefined) {
                (function(index) {
                        dayContent[index].classList.add('textAnimateOut');
                        dayContent[index].classList.remove('textAnimateIn');
                })(i);
            } else {
                return false;
            }
        }

    });



});
