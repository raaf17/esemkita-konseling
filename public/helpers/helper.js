const HELPER = {
    createCombo: async function (config) {
        const {
            el,
            valueField,
            displayField,
            url,
            withNull = false,
            grouped = false,
            chosen = false, // bisa ganti jadi select2 juga
            callback = () => { }
        } = config;

        try {
            const response = await fetch(url);
            const data = await response.json();

            el.forEach(id => {
                const select = document.getElementById(id);
                if (!select) return;

                // Clear isi lama
                select.innerHTML = '';

                // Tambah opsi null
                if (withNull) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.text = '-- Pilih --';
                    select.appendChild(option);
                }

                // Tambah data
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item[valueField];
                    option.text = item[displayField];
                    select.appendChild(option);
                });

                // Aktifkan Select2
                if (chosen && typeof $ !== 'undefined' && $.fn.select2) {
                    $('#' + id).select2({
                        placeholder: '-- Pilih --',
                        width: '100%',
                    });
                }

                // Callback selesai
                callback();
            });

        } catch (err) {
            console.error('Gagal mengambil data combo:', err);
        }
    }
};
