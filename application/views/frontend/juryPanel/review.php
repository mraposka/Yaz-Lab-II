<?php include 'header.php'; ?>
    <!-- Main Content -->
    <div id="app" class="flex-1 p-8 ml-64">
        <h2 class="text-3xl font-bold">Hoşgeldin, Juri!</h2>
        <p class="text-gray-600 mt-2">İlan Kriterlerini Yönetebilirsin.</p>

        <!-- İlan Formu -->
        <div v-if="showForm" class="bg-white p-6 rounded-lg shadow-lg mt-6">
            <h2 class="text-2xl font-bold mb-4">Kriter Formu</h2>
            <form method="POST" action="<?php echo base_url('jury/saveCriteria'); ?>" id="advert-form">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Departmant</label>
                    <select name="department" class="mt-1 block w-full px-4 py-2 border rounded-lg" required>
                        <?php foreach ($deps as $dep) : ?>
                        <option value="<?php echo $dep->id; ?>"><?php echo $dep->title; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Pozisyon</label>
                    <select name="position" class="mt-1 block w-full px-4 py-2 border rounded-lg" required>
                        <?php foreach ($positions as $position) : ?>
                        <option value="<?php echo $position->id; ?>"><?php echo $position->title; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Başvuru Koşulları</label>
                    <div id="conditions-container">
                        <!-- Dinamik kategori ekleme kısmı -->
                    </div>
                </div>
                <!-- Puan Koşulları -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Puan Koşulları</label>
                    <div id="score-conditions-container">
                        <!-- Dinamik puan koşulları eklenecek -->
                    </div>
                </div>

                <!-- Toplam Puan Alanı -->
                <div class="mb-4 flex gap-4">
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700">Toplam Puan (Asgari)</label>
                        <input name="totalScoreMin" type="number" class="mt-1 block w-full px-4 py-2 border rounded-lg"
                            required />
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700">Toplam Puan (Azami)</label>
                        <input name="totalScoreMax" type="number" class="mt-1 block w-full px-4 py-2 border rounded-lg"
                            required />
                    </div>
                </div>

                <!-- Eklenen puan koşulları listesi -->


                <!-- Baş Yazar Sayısı ve Toplam Makale Sayısı -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Baş Yazar Sayısı</label>
                    <input name="leadAuthorCount" type="number" class="mt-1 block w-full px-4 py-2 border rounded-lg"
                        required />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Toplam Makale Sayısı</label>
                    <input name="totalArticleCount" type="number" class="mt-1 block w-full px-4 py-2 border rounded-lg"
                        required />
                </div>
                <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600">
                    Ekle
                </button>
            </form>

            <div class="mt-4">
                <h3 class="text-lg font-medium text-gray-700">Eklenen Kategoriler</h3>
                <ul id="conditions-list" class="list-disc pl-5">
                    <!-- Eklenen kategoriler burada listelenecek -->
                </ul>
            </div>
            <div class="mt-4">
                <h3 class="text-lg font-medium text-gray-700">Eklenen Puan Koşulları</h3>
                <ul id="score-conditions-list" class="list-disc pl-5">
                    <!-- Listeye eklenenler buraya -->
                </ul>
            </div>

            

        </div>
        <h2 class="text-2xl font-bold mt-8 mb-4">Eklenen Kriterler</h2>
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="py-2 px-4 text-left">Pozisyon</th>
                    <th class="py-2 px-4 text-left">Departman</th>
                    <th class="py-2 px-4 text-center">Belge Kuralları</th>
                    <th class="py-2 px-4 text-center">Puan Kuralları</th>
                    <th class="py-2 px-4 text-center">Sil</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rules)): ?>
                    <?php foreach ($rules as $rule): ?>
                        <tr class="border-b">
                            <td class="py-2 px-4"><?php echo $rule->position_title;?></td>
                            <td class="py-2 px-4"><?php echo $rule->department_title;?></td>
                            <td class="py-2 px-4"><?php echo $rule->paperrules;?></td>
                            <td class="py-2 px-4"><?php echo $rule->scorerules;?></td>
                            <td class="py-2 px-4 text-center">
                                <form method="POST" action="<?php echo base_url('jury/deleteCriteria'); ?>" onsubmit="return confirm('Bu kriteri silmek istediğinizden emin misiniz?');">
                                    <input type="hidden" name="id" value="<?php echo $rule->id; ?>">
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="py-2 px-4 text-center text-gray-500">Henüz kayıtlı kriter yok.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
    </div>
    </div> <!-- #app kapanışı -->

<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            showForm: true, // İsterse bunu false yapıp bir butona basınca gösterebilirsin.
        };
    }
}).mount('#app');
</script>

    <script>
            const categories = {
                A: [1, 9],
                B: [1, 12],
                C: [1, 8],
                D: [1, 6],
                E: [1, 4],
                F: [1, 4],
                G: [1, 8],
                H: [1, 28],
                I: [1, 13],
                J: [1, 19],
                K: [1, 12],
                L: [1, 105]
            };

            let conditions = [];

            // Kategori dropdownları ve sayı inputlarını gösterme
            function showCategoryInputs() {
                const container = document.getElementById('conditions-container');

                const conditionDiv = document.createElement('div');
                conditionDiv.classList.add('mb-4', 'flex', 'gap-4');

                // Kategori dropdown 1
                const category1 = document.createElement('select');
                category1.name = 'category[]';
                category1.classList.add('mt-1', 'block', 'w-full', 'px-4', 'py-2', 'border', 'rounded-lg');
                category1.required = true;

                // Kategori dropdown 2
                const category2 = document.createElement('select');
                category2.name = 'category[]';
                category2.classList.add('mt-1', 'block', 'w-full', 'px-4', 'py-2', 'border', 'rounded-lg');
                category2.required = true;

                // Sayı inputu
                const numberInput = document.createElement('input');
                numberInput.type = 'number';
                numberInput.name = 'quantity[]';
                numberInput.classList.add('mt-1', 'block', 'w-full', 'px-4', 'py-2', 'border', 'rounded-lg');
                numberInput.required = true;

                // Kategori seçimi (A1 - A9 gibi seçenekler)
                Object.keys(categories).forEach(category => {
                    for (let i = categories[category][0]; i <= categories[category][1]; i++) {
                        let option1 = document.createElement('option');
                        option1.value = `${category}${i}`;
                        option1.textContent = `${category}${i}`;
                        category1.appendChild(option1);

                        let option2 = document.createElement('option');
                        option2.value = `${category}${i}`;
                        option2.textContent = `${category}${i}`;
                        category2.appendChild(option2);
                    }
                });

                // Ekle butonu
                const addButton = document.createElement('button');
                addButton.type = 'button';
                addButton.classList.add('bg-blue-500', 'text-white', 'py-2', 'px-4', 'rounded-lg', 'hover:bg-blue-600');
                addButton.textContent = 'Ekle';

                addButton.addEventListener('click', function() {
                    const category1Value = category1.value;
                    const category2Value = category2.value;
                    const quantity = numberInput.value;

                    // Kuralları diziye kaydet
                    if (category1Value && category2Value && quantity) {
                        conditions.push({
                            categories: `${category1Value}-${category2Value}`,
                            quantity: quantity
                        });

                        // Form verisi (örneğin, hidden input ile gönderilebilir)
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'conditions[]';
                        hiddenInput.value = `${category1Value}-${category2Value}=${quantity}`;
                        document.forms[0].appendChild(hiddenInput);

                        // Eklenen kategoriyi listeye ekle
                        const listItem = document.createElement('li');
                        listItem.textContent = `${category1Value}-${category2Value} = ${quantity}`;

                        // Silme butonu
                        const deleteButton = document.createElement('button');
                        deleteButton.classList.add('text-red-500', 'ml-2');
                        deleteButton.textContent = 'Sil';
                        deleteButton.addEventListener('click', function() {
                            // Silme işlemi
                            listItem.remove();
                            conditions = conditions.filter(condition => condition.categories !==
                                `${category1Value}-${category2Value}`);
                            toggleCategoryRequired();
                        });

                        listItem.appendChild(deleteButton);
                        document.getElementById('conditions-list').appendChild(listItem);

                        // Form elemanlarını sıfırla
                        category1.value = '';
                        category2.value = '';
                        numberInput.value = '';

                        toggleCategoryRequired();
                    }
                });

                // Kategori ve sayıyı ekle
                conditionDiv.appendChild(category1);
                conditionDiv.appendChild(category2);
                conditionDiv.appendChild(numberInput);
                conditionDiv.appendChild(addButton);
                container.appendChild(conditionDiv);
            }

            // İlk başta kategorileri göster
            showCategoryInputs();

            // Kategorilerin boş olup olmadığına göre required kontrolü
            function toggleCategoryRequired() {
                const categoryFields = document.querySelectorAll('select[name="category[]"]');
                const numberFields = document.querySelectorAll('input[name="quantity[]"]');

                // Eğer kategori eklenmemişse, kategoriler required olmalı
                if (conditions.length === 0) {
                    categoryFields.forEach(field => field.setAttribute('required', 'required'));
                    numberFields.forEach(field => field.setAttribute('required', 'required'));
                } else {
                    categoryFields.forEach(field => field.removeAttribute('required'));
                    numberFields.forEach(field => field.removeAttribute('required'));
                }
            }

            // Form submit öncesi koşul kontrolü
            document.getElementById('advert-form').addEventListener('submit', function(event) {
                if (conditions.length === 0) {
                    alert('Lütfen en az bir kategori seçin.');
                    event.preventDefault(); // Form gönderimini engelle
                }
            });
            let scoreConditions = [];

            function showScoreInputs() {
                const container = document.getElementById('score-conditions-container');

                const conditionDiv = document.createElement('div');
                conditionDiv.classList.add('mb-4', 'flex', 'gap-4');

                const category1 = document.createElement('select');
                category1.name = 'scoreCategory[]';
                category1.classList.add('mt-1', 'block', 'w-full', 'px-4', 'py-2', 'border', 'rounded-lg');
                category1.required = true;

                const category2 = document.createElement('select');
                category2.name = 'scoreCategory[]';
                category2.classList.add('mt-1', 'block', 'w-full', 'px-4', 'py-2', 'border', 'rounded-lg');
                category2.required = true;

                const minInput = document.createElement('input');
                minInput.type = 'number';
                minInput.name = 'scoreMin[]';
                minInput.placeholder = 'Asgari';
                minInput.classList.add('mt-1', 'block', 'w-full', 'px-4', 'py-2', 'border', 'rounded-lg');
                minInput.required = true;

                const maxInput = document.createElement('input');
                maxInput.type = 'number';
                maxInput.name = 'scoreMax[]';
                maxInput.placeholder = 'Azami';
                maxInput.classList.add('mt-1', 'block', 'w-full', 'px-4', 'py-2', 'border', 'rounded-lg');
                maxInput.required = true;

                Object.keys(categories).forEach(category => {
                    for (let i = categories[category][0]; i <= categories[category][1]; i++) {
                        const val = `${category}${i}`;
                        const option1 = new Option(val, val);
                        const option2 = new Option(val, val);
                        category1.add(option1);
                        category2.add(option2);
                    }
                });

                const addButton = document.createElement('button');
                addButton.type = 'button';
                addButton.classList.add('bg-purple-500', 'text-white', 'py-2', 'px-4', 'rounded-lg',
                    'hover:bg-purple-600');
                addButton.textContent = 'Ekle';

                addButton.addEventListener('click', () => {
                    const cat1 = category1.value;
                    const cat2 = category2.value;
                    const min = minInput.value;
                    const max = maxInput.value;

                    if (cat1 && cat2 && min && max) {
                        const rule = `${cat1}-${cat2}`;
                        scoreConditions.push({
                            categories: rule,
                            min,
                            max
                        });

                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'scoreConditions[]';
                        hiddenInput.value = `${rule}=${min}-${max}`;
                        document.forms[0].appendChild(hiddenInput);

                        const li = document.createElement('li');
                        li.textContent = `${rule} = ${min} - ${max}`;

                        const delBtn = document.createElement('button');
                        delBtn.classList.add('text-red-500', 'ml-2');
                        delBtn.textContent = 'Sil';
                        delBtn.addEventListener('click', () => {
                            li.remove();
                            scoreConditions = scoreConditions.filter(s => s.categories !== rule);
                            toggleScoreRequired();
                        });

                        li.appendChild(delBtn);
                        document.getElementById('score-conditions-list').appendChild(li);

                        category1.value = '';
                        category2.value = '';
                        minInput.value = '';
                        maxInput.value = '';

                        toggleScoreRequired();
                    }
                });

                conditionDiv.append(category1, category2, minInput, maxInput, addButton);
                container.appendChild(conditionDiv);
            }

            // Sayfa yüklendiğinde göster
            showScoreInputs();

            function toggleScoreRequired() {
                const cats = document.querySelectorAll('select[name="scoreCategory[]"]');
                const mins = document.querySelectorAll('input[name="scoreMin[]"]');
                const maxs = document.querySelectorAll('input[name="scoreMax[]"]');

                if (scoreConditions.length === 0) {
                    [...cats, ...mins, ...maxs].forEach(field => field.setAttribute('required', 'required'));
                } else {
                    [...cats, ...mins, ...maxs].forEach(field => field.removeAttribute('required'));
                }
            }

            // Form gönderim kontrolü
            document.getElementById('advert-form').addEventListener('submit', function(event) {
                if (scoreConditions.length === 0) {
                    alert('Lütfen en az bir puan koşulu girin.');
                    event.preventDefault();
                }
            });
            </script>
</body>

</html>