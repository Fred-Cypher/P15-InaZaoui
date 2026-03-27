document.addEventListener('DOMContentLoaded', () => {

    const switches = document.querySelectorAll('.js-switch');

    switches.forEach(switchInput => {
        switchInput.addEventListener('change', function () {
            const url = this.dataset.url;
            const userId = this.dataset.id;
            const statusCell = document.getElementById(`status-text-${userId}`)

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Erreur réseau');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if(this.checked) {
                        statusCell.innerHTML = '<span class="badge bg-success">Actif</span>';
                    } else {
                        statusCell.innerHTML = '<span class="badge bg-danger">Bloqué</span>'
                    }
                    console.log("Statut mis à jour en base de données");
                }
            })
            .catch(error => {
                this.checked = !this.checked;
                alert("Erreur lors de la modification du statut");
            });
        });
    });
});
