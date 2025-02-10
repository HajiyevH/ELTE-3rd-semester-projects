import {easyMap1,easyMap2,easyMap3,easyMap4,easyMap5,hardMap1,hardMap2,hardMap3,hardMap4,hardMap5,getNextTileAndDirections,getTileImageSrc} from './maps.js';

let selectedDifficulty = null;

const playerNameInput = document.querySelector("#player-name");
const difficultyButtons = document.querySelectorAll(".difficulty");
const startButton = document.querySelector(".start-button");
const rulesButton = document.querySelector("#rules-button"); 
const closeDescriptionButton = document.querySelector("#close-description");

rulesButton.addEventListener("click", () => {
    document.querySelector('#description').style.display = 'block';
    document.querySelector('#game').style.display = 'none';
    document.querySelector('#menu').style.display = 'none';
});

closeDescriptionButton.addEventListener("click", () => {
    document.querySelector('#description').style.display = 'none';
    document.querySelector('#menu').style.display = 'block';
});

const backToMenuButton = document.querySelector("#back-to-menu");
backToMenuButton.addEventListener("click", () => {
    document.querySelector('#game').style.display = 'none';
    document.querySelector('#menu').style.display = 'block';
    stopTimer(); 
    validateForm();
});

function validateForm() {
    if (playerNameInput.value.trim() !== "" && selectedDifficulty){       
        startButton.style.opacity = "1"; 
        startButton.disabled = false;
    } else {
        startButton.style.opacity = "0.2"; 
        startButton.disabled = true;
    }
}

validateForm();

playerNameInput.addEventListener("input", validateForm);
difficultyButtons.forEach(button => {
    button.addEventListener("click", (event) => {
        const classList = event.target.classList;
        if (classList.contains("easy")) {
            selectedDifficulty = "easy";
        } else if (classList.contains("hard")) {
            selectedDifficulty = "hard";
        }
        difficultyButtons.forEach(btn => btn.classList.remove("selected"));
        event.target.classList.add("selected");
        validateForm();
    });
});

startButton.addEventListener("click", () => {
    startGame(selectedDifficulty);
});

let timeElapsed = 0;
let timerInterval;

function startTimer() {
    timeElapsed = 0; 
    clearInterval(timerInterval); 
    const timerElement = document.querySelector("#timer");

    timerInterval = setInterval(() => {
        timeElapsed += 1; 
        if (timerElement) {
            timerElement.textContent = formatTime(timeElapsed); 
        }
    }, 1000);
}

function stopTimer() {
    clearInterval(timerInterval);
    console.log(`Total time to complete all maps: ${formatTime(timeElapsed)}`);
}


function formatTime(seconds) {
    if (isNaN(seconds)) {
        console.error("Invalid timeElapsed value:", seconds);
        return "00:00";
    }
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
}

function endGame(difficulty) {
    stopTimer();
    
    const playerName = playerNameInput.value.trim();

    const time = timeElapsed;

    addEntryToLeaderboard(difficulty, { name: playerName, time: time });

    displayLeaderboard(difficulty);
}

let Finished = false

function renderMap(map) {
    console.log(map);
    const mapContainer = document.querySelector('#map-container');
    mapContainer.innerHTML = '';

    map[0].forEach((_, colIndex) => {
        const colDiv = document.createElement('div');
        colDiv.classList.add('map-column');

        map.forEach((row, rowIndex) => {  
            const cellDiv = document.createElement('div');
            cellDiv.classList.add('map-cell');
            const cell = row[colIndex];
            cellDiv.setAttribute('data-tile', cell);
            cellDiv.setAttribute('data-row', rowIndex); 
            cellDiv.setAttribute('data-col', colIndex); 
            cellDiv.setAttribute('data-directions',['none','none'])
            let imgSrc;

            imgSrc = getTileImageSrc(cell);

            if (imgSrc) {
                const imgElement = document.createElement('img');
                imgElement.src = imgSrc;
                imgElement.alt = cell;
                imgElement.style.pointerEvents = 'none';
                cellDiv.appendChild(imgElement);
            }

            cellDiv.addEventListener('click', () => {
                let fin = changeTile(cellDiv ,cellDiv.getAttribute('data-directions')[0] );
                if(fin){Finished = true;}
            });

            colDiv.appendChild(cellDiv);
        });

        mapContainer.appendChild(colDiv);
    });
}


function validate(cell, direction, visited = new Set() , num = 0) {
    const mapContainer = document.querySelector('#map-container');
    const gridSize = mapContainer.children.length; 
    let tile = cell.getAttribute('data-tile');
    let row = parseInt(cell.getAttribute('data-row'));
    let col = parseInt(cell.getAttribute('data-col'))
    const totalCells = gridSize * gridSize - document.querySelectorAll('[data-tile="oasis"]').length;
    if (num >= totalCells) {
        console.log("Finished");
        console.log("Validated");
        return true;
    }
    if (tile === 'oasis' || visited.has(`${row}-${col}`)) {
        console.log("Tile is either an oasis or already visited, skipping...");
        return false;
    }

    visited.add(`${row}-${col}`);

    const neighbors = {
        top: null,
        bottom: null,
        left: null,
        right: null
    };

    if (row > 0) {
        neighbors.top = mapContainer.querySelector(`[data-row="${row - 1}"][data-col="${col}"]`);
    }

    if (row < gridSize - 1) {
        neighbors.bottom = mapContainer.querySelector(`[data-row="${row + 1}"][data-col="${col}"]`);
    }

    if (col > 0) {
        neighbors.left = mapContainer.querySelector(`[data-row="${row}"][data-col="${col - 1}"]`);
    }

    if (col < gridSize - 1) {
        neighbors.right = mapContainer.querySelector(`[data-row="${row}"][data-col="${col + 1}"]`);
    }
    
    if (direction == 'none') {
        return;
    } else if (direction == 'north' && neighbors.top) {
        let topDirections = neighbors.top.getAttribute('data-directions').split(',');

        if (topDirections.includes('south')) {
            const nextDirection = topDirections.find(dir => dir !== 'south');
            if (validate(neighbors.top, nextDirection, visited, num + 1)) {
                return true;
            }
        } else {
            console.log("No valid connection from top, skipping...");
            return false;
        }
    } else if (direction == 'south' && neighbors.bottom) {
        let bottomDirections = neighbors.bottom.getAttribute('data-directions').split(',');

        if (bottomDirections.includes('north')) {
            const nextDirection = bottomDirections.find(dir => dir !== 'north');
            if (validate(neighbors.bottom, nextDirection, visited, num + 1)) {
                return true;
            }

        } else {
            console.log("No valid connection from bottom, skipping...");
            return false;

        }
    } else if (direction == 'west' && neighbors.left) {
        let leftDirections = neighbors.left.getAttribute('data-directions').split(',');

        if (leftDirections.includes('east')) {
            const nextDirection = leftDirections.find(dir => dir !== 'east');
            if (validate(neighbors.left, nextDirection, visited, num + 1)) {
                return true;
            }

        } else {
            console.log("No valid connection from left, skipping...");
            return false;

        }
    } else if (direction == 'east' && neighbors.right) {
        let rightDirections = neighbors.right.getAttribute('data-directions').split(',');

        if (rightDirections.includes('west')) {
            const nextDirection = rightDirections.find(dir => dir !== 'west');
            if (validate(neighbors.right, nextDirection, visited, num + 1)) {
                return true;
            }

        } else {
            console.log("No valid connection from right, skipping...");
            return false;
        }
    }
    return false;
}

function changeTile(cellDiv) {
    let currentTile = cellDiv.getAttribute('data-tile');

    const { nextTile, nextDirections } = getNextTileAndDirections(currentTile);

    let imgSrc;

    imgSrc = getTileImageSrc(nextTile);

    cellDiv.querySelector('img').src = imgSrc;
    cellDiv.setAttribute('data-tile', nextTile);
    cellDiv.setAttribute('data-directions',nextDirections)

    return validate(cellDiv, cellDiv.getAttribute('data-directions').split(',')[0]);
}
function getRandomElement(arr) {
    const randomIndex = Math.floor(Math.random() * arr.length);
    return arr[randomIndex];
}

function startGame(difficulty) {
    document.querySelector('#menu').style.display = 'none';
    document.querySelector('#game').style.display = 'block';

    const easyMaps = [easyMap1,easyMap2,easyMap3,easyMap4,easyMap5];
    const hardMaps = [hardMap1, hardMap2, hardMap3, hardMap4, hardMap5];
    
    const maps = difficulty === 'easy' ? easyMaps : hardMaps;

    let currentMap = getRandomElement(maps);

    startTimer();
    renderMap(currentMap);
    displayLeaderboard(selectedDifficulty);

    const playerName = playerNameInput.value.trim();
    const playerDisplay = document.querySelector("#player-display");
    playerDisplay.textContent = `${playerName}`;

    const checkInterval = setInterval(() => {
        if (Finished) {
            clearInterval(checkInterval); 
            Finished = false;             
            endGame(difficulty);                
        }
    }, 100); 
}

function loadLeaderboard(difficulty) {
    const savedLeaderboard = JSON.parse(localStorage.getItem(`leaderboard_${difficulty}`));
    return savedLeaderboard ? savedLeaderboard : [];
}

function saveLeaderboard(difficulty, leaderboard) {
    localStorage.setItem(`leaderboard_${difficulty}`, JSON.stringify(leaderboard));
}

function addEntryToLeaderboard(difficulty, entry) {
    const leaderboard = loadLeaderboard(difficulty);
    leaderboard.push(entry);
    saveLeaderboard(difficulty, leaderboard);
}

function displayLeaderboard(difficulty) {
    const leaderboard = loadLeaderboard(difficulty);
    const leaderboardContainer = document.querySelector('#leaderboard');

    while (leaderboardContainer.firstChild) {
        leaderboardContainer.removeChild(leaderboardContainer.firstChild);
        console.log("mese");
    }

    const title = document.createElement('h3');
    title.textContent = `${difficulty.charAt(0).toUpperCase() + difficulty.slice(1)} Leaderboard`;
    leaderboardContainer.appendChild(title);
    leaderboard.sort((a, b) => a.time - b.time);

    leaderboard.slice(0, 8).forEach((entry, index) => {
        const entryDiv = document.createElement('div');
        entryDiv.classList.add('leaderboard-entry');
        entryDiv.textContent = `${index + 1}. ${entry.name} - ${formatTime(entry.time)}`;
        leaderboardContainer.appendChild(entryDiv);
    });
}
//I only used it to clear problematic times when writing the code.
function clearLeaderboard(difficulty) {
    localStorage.setItem(`leaderboard_${difficulty}`, JSON.stringify([]));
    
    if (difficulty === 'easy') {
        leaderboardEasy = [];
    } else {
        leaderboardHard = [];
    }
    displayLeaderboard(difficulty);
}
