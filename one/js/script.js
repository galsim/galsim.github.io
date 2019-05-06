window.onload = function() {
    let menuButton = document.querySelector('#menuToggle input');
    let menuItem = document.querySelector('#menu_item');
    let visible = true
    let openMenu = function() {
        if (visible) {
            menuItem.style.display = 'block';
            visible = false;
        } else {
            menuItem.style.display = 'none';
            visible = true;
        }
        
    }
    menuButton.onclick = openMenu;

    let likesMenu = document.querySelector('#likes_item');
    let likesList = likesMenu.querySelectorAll('li');
    let likesCard = document.querySelectorAll('.interesting__news');
    let selectCard = document.querySelector('.active_card');
    let selectLi = likesMenu.querySelector('.active');

    likesMenu.onclick = function(event) {
        var target = event.target; 
      
        if (target.tagName != 'LI') return; 
      
        classActive(target); 
    };
      
    function classActive(node) {
        if (selectLi) {
            selectLi.classList.remove('active');
            selectCard.classList.remove('active_card')
        }
        selectLi = node;
        selectLi.classList.add('active');

        for (let i = 0; i < likesList.length; i++) {
            if (likesList[i].classList.contains('active')) {
                likesCard[i].classList.add('active_card')
                selectCard = likesCard[i];
            }

        }
    };

    let slider = document.querySelector('.slider')

    slider.onclick = function(event) {
        let target = event.target; 
      
        if (target.tagName != 'IMG') return; 
      
        changeImage(target); 
      };

    function changeImage(node) {
        slider.querySelector('.main_image').src = node.src
    }
}