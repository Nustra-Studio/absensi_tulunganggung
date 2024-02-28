                judulSelector.on('change', function() {
                    selectedJudul = $('.select2').val();
                    filterAndDisplayData();
                    var selectedItem = null;

console.log("Selected Judul: " + selectedJudul);
                });
