const likes = document.querySelectorAll('.like');

likes.forEach(like => {
    let countLike = 0;
    like.addEventListener('click', () => {
        if (countLike === 0) {
            like.classList.add('anim-like');
            countLike = 1;
        } else {
            like.classList.remove('anim-like');
            like.style.backgroundPosition = 'left';
            countLike = 0;
        }
    });

    like.addEventListener('animationend', () => {
        like.classList.remove('anim-like');
        like.style.backgroundPosition = 'right';
    });
});


const notif = document.querySelector('.notifications');
let countNotif = 0;

notif.addEventListener('click', () => {
    notif.classList.add('anim-notif');
    countNotif++;

    if (countNotif > 0) {
        notif.style.backgroundPosition = 'right';
    }
});

notif.addEventListener('animationend', () => {
    notif.classList.remove('anim-notif');
});








function ouvrirPopup(id) {
    let popup = document.getElementById(id);
    if (popup) {
        popup.style.display = "block";
    } else {
        console.error("Popup non trouvé !");
    }
}

function fermerPopup(id) {
    let popup = document.getElementById(id);
    if (popup) {
        popup.style.display = "none";
    } else {
        console.error("Popup non trouvé !");
    }
}


// Ferme la modale si on clique en dehors
window.onclick = function(event) {
    let popups = document.querySelectorAll(".modal");
    popups.forEach(popup => {
        if (event.target === popup) {
            popup.style.display = "none";
        }
    });
};


