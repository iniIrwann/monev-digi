document.getElementById('fileInput').addEventListener('change', function() {
  const preview = document.getElementById('previewFiles');
  preview.innerHTML = ""; // reset preview

  [...this.files].forEach(file => {
    let fileDiv = document.createElement('div');
    fileDiv.classList.add("p-2", "border", "rounded", "d-flex", "align-items-center", "justify-content-between");
    fileDiv.style.width = "250px";

    if(file.type.startsWith("image/")) {
      // kalau gambar tampilkan thumbnail
      let img = document.createElement("img");
      img.src = URL.createObjectURL(file);
      img.style.width = "40px";
      img.style.height = "40px";
      img.classList.add("me-2", "rounded");
      fileDiv.appendChild(img);
    } else {
      // kalau bukan gambar (pdf dll) tampilkan icon
      let icon = document.createElement("i");
      icon.classList.add("bi", "bi-file-earmark-pdf-fill", "fs-3", "text-danger", "me-2");
      fileDiv.appendChild(icon);
    }

    // nama file
    let span = document.createElement("span");
    span.textContent = file.name;
    span.classList.add("flex-grow-1");
    fileDiv.appendChild(span);

    // tombol hapus
    let del = document.createElement("button");
    del.innerHTML = "X";
    del.classList.add("btn", "btn-sm", "btn-outline-danger", "ms-2");
    del.onclick = () => fileDiv.remove();
    fileDiv.appendChild(del);

    preview.appendChild(fileDiv);
  });
});