const size = 8;
const hitSound = new Audio("sounds/hit.mp3");

const playerBoard = document.getElementById("playerBoard");
const computerBoard = document.getElementById("computerBoard");

function buildBoards() {
    playerBoard.innerHTML = "";
    computerBoard.innerHTML = "";

    for (let r = 0; r < size; r++) {
        for (let c = 0; c < size; c++) {

            const pCell = document.createElement("div");
            pCell.className = "cell";
            pCell.dataset.row = r;
            pCell.dataset.col = c;
            playerBoard.appendChild(pCell);

            const cCell = document.createElement("div");
            cCell.className = "cell";
            cCell.onclick = () => fire(r, c, cCell);
            computerBoard.appendChild(cCell);
        }
    }
}

function fire(row, col, cell) {
    if (cell.classList.contains("hit") || cell.classList.contains("miss")) return;

    fetch("game.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ row, col })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.player) return;

        cell.classList.add(data.player.hit ? "hit" : "miss");
        if(data.player.hit)
        {
            hitSound.play();
        }

        const idx = data.computer.row * size + data.computer.col;
        const pCell = playerBoard.children[idx];
        pCell.classList.add(data.computer.hit ? "hit" : "miss");
        if(data.computer.hit)
        {
            hitSound.play();
        }
    });
}

function resetGame() {
    fetch("game.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ reset: true })
    }).then(() => buildBoards());
}

buildBoards();
