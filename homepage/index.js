const container = document.getElementById("cardContainer");
const genre = document.getElementById("genre");
let dataBuku = [];

fetch("data.json")
  .then((response) => response.json())
  .then((data) => {
    dataBuku = data;
    renderBuku(dataBuku);
  })
  .catch((error) => {
    console.error("Gagal mengambil data JSON:", error);
  });

function renderBuku(data) {
  container.innerHTML = "";

  data.forEach((buku) => {
    container.innerHTML += `
 
      <div class="card" id="cardContainer">
        <img src="${buku.cover}">
        <div class="card-body">
          <span class="genre">${buku.genre}</span>
          <h3>${buku.judul}</h3>
          <p>${buku.penulis}</p>
          <p>Tahun: ${buku.tahun}</p>
        </div>
      </div>

    `;
  });
}
