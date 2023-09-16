document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const responseArea = document.getElementById('responseArea');
    const resultArea = document.getElementById('resultArea');
    const restartButton = document.getElementById('restartButton');
    let currentCharacter = null;

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('server.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            currentCharacter = data;
            responseArea.textContent = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    restartButton.addEventListener('click', () => {
        resultArea.innerHTML = '';
        restartButton.style.display = 'none';

        fetch('server.php?reset=true')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'reset_successful') {
                form.dispatchEvent(new Event('submit'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    document.addEventListener('keypress', (e) => {
        if (currentCharacter) {
            const isCorrect = e.key.toLowerCase() === currentCharacter.toLowerCase();
            
            responseArea.textContent = isCorrect ? 'Richtig' : 'Falsch';
            responseArea.style.color = isCorrect ? 'green' : 'red';
            
            currentCharacter = null;
    
            setTimeout(() => {
                responseArea.textContent = '';
                responseArea.style.color = 'black';
    
                fetch('server.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'keyPress': e.key
                    })
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "STOP") {
                        fetch('server.php?stats=true')
                        .then(response => response.json())
                        .then(stats => {
                            if (!stats.error) {
                                resultArea.innerHTML = `
                                    <p>Gesamtzeit: ${stats.totalTime} Sekunden</p>
                                    <p>Durchschnittliche Zeit: ${stats.averageTime} Sekunden</p>
                                    <p>Richtige Tastendrücke: ${stats.correctPresses}</p>
                                    <p>Falsche Tastendrücke: ${stats.wrongPresses}</p>
                                `;
                                restartButton.style.display = 'block';
                            } else {
                                resultArea.innerHTML = `<p>${stats.error}</p>`;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    } else {
                        responseArea.textContent = data;
                        currentCharacter = data;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }, 1000);
        }
    }); 
});
