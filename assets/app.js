import './styles/app.scss';
import './bootstrap';

const btnAddComment = document.querySelector('.btn-addComment');
const formComment = document.querySelector('#form-comment');
const btnsLike = document.querySelectorAll('.btn-like');


if (btnAddComment && formComment) {
  btnAddComment.addEventListener('click', (e) => {
    e.preventDefault();
    if (btnAddComment.getAttribute('author')) {
      formComment.submit();
    } else {
      const response = confirm('Vous devez être connecté pour commenter !');
      if (response) {
        location.href = 'http://localhost:8000/login';
      }
    }
  })
}

if (btnsLike) {
  btnsLike.forEach((btn) => {
    btn.addEventListener('click', async function (e) {
      e.preventDefault();

      const response = await fetch(this.href);
      const data = await response.json();
      console.log(data.count);
      this.querySelector('.count').innerHTML = data.count

      if (data.count === 0) this.classList.add('btn-secondary')
      data.liked ? this.classList.add('btn-success') : this.classList.remove('btn-success')
    })
  })

}




