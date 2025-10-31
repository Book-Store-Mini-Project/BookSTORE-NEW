const arrayFeaturedBook = [
  {
    id: 1,
    imageUrl: "assets/product page images/S4.jpg",
    bookName: "Gangaru Puda Kavi",
    author: "Liam Carter",
    price: "950",
    description: "A gripping fantasy novel set in a parallel world.",
  },
  {
    id: 2,
    imageUrl: "assets/product page images/S5.jpg",
    bookName: "Sinhala Sahithya",
    author: "Sophia Wells",
    price: "2700",
    description: "An epic tale of betrayal and redemption.",
  },
  {
    id: 3,
    imageUrl: "assets/product page images/S6.jpg",
    bookName: "Viyarana Yathura",
    author: "Ethan Blake",
    price: "1700",
    description: "A time-travel adventure with unexpected consequences.",
  },
  {
    id: 4,
    imageUrl: "assets/product page images/T1.jpg",
    bookName: "Thirukkaral",
    author: "W.H.Drew",
    price: "1800",
    description: "A thrilling mystery novel with unexpected twists.",
  },
  {
    id: 5,
    imageUrl: "assets/product page images/T2.jpg",
    bookName: "Indulaima",
    author: "D.H.Kavilma",
    price: "1200",
    description: "A poetic journey through love and loss.",
  },
  {
    id: 6,
    imageUrl: "assets/product page images/T3.jpg",
    bookName: "Pongatuk",
    author: "Ramanisanthiran",
    price: "2300",
    description: "A cyber-thriller that explores the dangers of encryption.",
  },
  {
    id: 10,
    imageUrl: "assets/product page images/4.png",
    bookName: "Sell English",
    author: "Liam Carter",
    price: "950",
    description: "A gripping fantasy novel set in a parallel world.",
  },
  {
    id: 12,
    imageUrl: "assets/product page images/6.png",
    bookName: "Bussiness Report",
    author: "Ethan Blake",
    price: "1700",
    description: "A time-travel adventure with unexpected consequences.",
  },
];

const arrayNewArrivalBook=[
  {
    id: 1,
    imageUrl: "assets/product page images/S3.jpg",
    bookName: "Ganga Addara",
    author: "Dan Croft",
    price: "2300",
    description: "A cyber-thriller that explores the dangers of encryption.",
  },
  {
    id: 2,
    imageUrl: "assets/product page images/S2.jpg",
    bookName: "Sinahal Anduwa",
    author: "Ella Brooks",
    price: "1200",
    description: "A poetic journey through love and loss.",
  },
  {
    id: 9,
    imageUrl: "assets/product page images/3.png",
    bookName: "Student Diary",
    author: "Dan Croft",
    price: "2300",
    description: "A cyber-thriller that explores the dangers of encryption.",
  },
  {
    id: 17,
    imageUrl: "assets/product page images/T5.jpg",
    bookName: "Katikiyan",
    author: "Sophia Wells",
    price: "2700",
    description: "An epic tale of betrayal and redemption.",
  },
  {
    id: 18,
    imageUrl: "assets/product page images/T6.jpg",
    bookName: "Penmani",
    author: "Ethan Blake",
    price: "1700",
    description: "A time-travel adventure with unexpected consequences.",
  }

];

// Silder case=========>
let slideIndex = 1;

function renderSlider() {
  // Slideshow setup
  showSlides(slideIndex);

  // Add event listeners for the dots
  const dots = document.getElementsByClassName("dot");
  for (let i = 0; i < dots.length; i++) {
    dots[i].addEventListener("click", function() {
      currentSlide(i + 1); // Click on a dot, go to the respective slide
    });
  }
  
  function currentSlide(n) {
    showSlides(slideIndex = n);
  }

  function showSlides(n) {
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");

    // If we exceed the total number of slides, start again from 1
    if (n > slides.length) {
      slideIndex = 1;
    }
    // If we go below 1, go to the last slide
    if (n < 1) {
      slideIndex = slides.length;
    }

    // Hide all slides
    for (let i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }

    // Remove the "active" class from all dots
    for (let i = 0; i < dots.length; i++) {
      dots[i].classList.remove("active");
    }

    // Show the current slide and activate the corresponding dot
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].classList.add("active");
  }
}


document.addEventListener("DOMContentLoaded", () => {
  //slider call
  renderSlider();

  const renderBooks = (container,array) => {
  array.forEach((data) => {
      container.innerHTML += `
        <div class="containerCard">
            <div class="imageBox">
                <img src="${data.imageUrl}" class="card-img" />
            </div>
            <div class="productDetail">
                <p class="bookname">${data.bookName}</p>
                <div class="bookauthor">
                    <p class="bookauthor">Author : ${data.author}</p>
                </div>
                <p class="price">Rs.${data.price}</p>
                <p class="rate">Ratings : 5.0⭐</p>
                <button class="addCart" 
                    data-id="${data.id}"
                    data-title="${data.bookName}"
                    data-price="${data.price}"
                    data-image="${data.imageUrl}">
                    Add to Cart
                </button>
            </div>
        </div>`;
    });
  };

  const container1 = document.querySelector(".container-Featured-Book");
  const container2 = document.querySelector(".container-New-Book");
  
  renderBooks(container1,arrayFeaturedBook);
  renderBooks(container2,arrayNewArrivalBook);

  //==================> Cart functionality(becuase there are some product show on home page)<======

  // get all button on the class name call addCart
  const buttons = document.querySelectorAll(".addCart");
  // one of  button perform click
  buttons.forEach((button) => {
    button.addEventListener("click", () => {
      // create product object
      const product = {
        id: button.dataset.id,
        title: button.dataset.title,
        price: parseFloat(button.dataset.price),
        image: button.dataset.image,
        quantity: 1,
      };

      // get cart form localStorage to check product avilibility
      let cart = JSON.parse(localStorage.getItem("Cart")) || [];

      // check product
      let isProduct = null;
      for (let i = 0; i < cart.length; i++) {
        if (cart[i].id === product.id) {
          isProduct = cart[i];
          break;
        }
      }

      // if product IN
      if (isProduct) {
        isProduct.quantity += 1;
      } else {
        cart.push(product);
      }

      // save back cart on local storeage
      localStorage.setItem("Cart", JSON.stringify(cart));
      alert(`${product.title} added to cart!`);
    });
  });
});
