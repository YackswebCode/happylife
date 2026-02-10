<script>
document.addEventListener("DOMContentLoaded", function(){

    const statesData = {
        'AREWA': ['Kano Central', 'Nasarawa Center'],
        'ODUDUWA': ['Lagos Mainland', 'Ibadan Central'],
        'BIAFRA': ['Port Harcourt Main', 'Enugu North']
    };

    const pickupCentersData = {
        'Kano Central': ['Kano Shop 1','Kano Shop 2'],
        'Nasarawa Center': ['Nasarawa Shop 1'],
        'Lagos Mainland': ['Lagos Shop'],
        'Ibadan Central': ['Ibadan Shop'],
        'Port Harcourt Main': ['PH Shop A'],
        'Enugu North': ['Enugu Shop B']
    };

    const productsData = @json($productsJs);

    const countrySelect = document.getElementById('country');
    const stateSelect = document.getElementById('state');
    const stateSelectGroup = document.getElementById('stateSelectGroup');
    const stateInputGroup = document.getElementById('stateInputGroup');
    const pickupSelect = document.getElementById('pickup_center');
    const productSelect = document.getElementById('product_id');
    const packagesRow = document.getElementById('packagesRow');

    // COUNTRY CHANGE
    countrySelect.addEventListener('change', function(){
        const country = this.value;
        if(country==='OTHERS'){
            stateSelectGroup.classList.add('hidden');
            stateInputGroup.classList.remove('hidden');
            stateSelect.required = false;
            stateInputGroup.querySelector('input').required = true;
            pickupSelect.disabled = true;
            productSelect.disabled = true;
        } else {
            stateSelectGroup.classList.remove('hidden');
            stateInputGroup.classList.add('hidden');
            stateSelect.required = true;
            stateInputGroup.querySelector('input').required = false;

            stateSelect.innerHTML = '<option value="" disabled selected>Select state</option>';
            if(statesData[country]){
                statesData[country].forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s;
                    opt.textContent = s;
                    stateSelect.appendChild(opt);
                });
            }
            stateSelect.disabled = false;
            pickupSelect.disabled = true;
            productSelect.disabled = true;
        }
    });

    // STATE CHANGE
    stateSelect.addEventListener('change', function(){
        const state = this.value;
        pickupSelect.innerHTML = '<option value="" selected>Select pickup center</option>';
        if(pickupCentersData[state]){
            pickupCentersData[state].forEach(c => {
                const opt = document.createElement('option');
                opt.value = c;
                opt.textContent = c;
                pickupSelect.appendChild(opt);
            });
            pickupSelect.disabled = false;
        } else {
            pickupSelect.disabled = true;
        }
        productSelect.disabled = true;
    });

    // PACKAGE CHANGE
    packagesRow.addEventListener('change', function(e){
        if(e.target.classList.contains('package-radio')){
            const packageId = e.target.value;
            productSelect.innerHTML = '<option value="" disabled selected>Select a product</option>';
            if(productsData[packageId]){
                productsData[packageId].forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    opt.textContent = p.name;
                    productSelect.appendChild(opt);
                });
                productSelect.disabled = false;
            } else {
                productSelect.disabled = true;
            }
        }
    });
});
</script>
