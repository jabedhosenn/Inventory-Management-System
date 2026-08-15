<!-- Edit Customer Modal -->
<div class="modal fade" id="customerEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="customerEditForm">
                <input type="hidden" id="customerEditId" value="">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="customerEditName">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="customerEditName" class="form-control" placeholder="e.g. Electronics" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="customerEditEmail">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="customerEditEmail" class="form-control" placeholder="e.g. john@example.com" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="customerEditMobile">Mobile <span class="text-danger">*</span></label>
                        <input type="text" name="mobile" id="customerEditMobile" class="form-control" placeholder="e.g. 123-456-7890" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="customerEditAddress">Address <span class="text-danger">*</span></label>
                        <input type="text" name="address" id="customerEditAddress" class="form-control" placeholder="e.g. 123 Main St" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="customerEditDescription">Description</label>
                        <textarea name="description" id="customerEditDescription" class="form-control" rows="3" placeholder="Optional description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="customerEditSaveBtn">
                        <i class="bi bi-check2-circle me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        async function getCustomerInfo(id){
            let URL = '{{ url("/api/v1/customers") }}/' + id;
            let token = localStorage.getItem('token');

            try{
                let response = await axios.get(URL, {
                    headers: { Authorization: 'Bearer ' + token }
                });
                let data = response.data['data'];
                if(data){
                    document.getElementById('customerEditId').value = data['id'] || id;
                    document.getElementById('customerEditName').value = data['name'] || '';
                    document.getElementById('customerEditEmail').value = data['email'] || '';
                    document.getElementById('customerEditMobile').value = data['mobile'] || '';
                    document.getElementById('customerEditAddress').value = data['address'] || '';
                    document.getElementById('customerEditDescription').value = data['description'] || '';
                }
            }catch (err) {
                showErrorToast(getErrorMessage(err, 'Failed to load customer.'));
            }
        }

        async function editCustomer(id){
            document.getElementById('customerEditId').value = id;
            await getCustomerInfo(id);
            let modalEl = document.getElementById('customerEditModal');
            let modal = new bootstrap.Modal(modalEl);
            modal.show();

        }

        async function doEditCustomer() {
            let id = document.getElementById('customerEditId').value.trim();
            let nameValue = document.getElementById('customerEditName').value.trim();
            let addressValue = document.getElementById('customerEditAddress').value.trim();
            let descriptionValue = document.getElementById('customerEditDescription').value.trim();
            let saveBtn = document.getElementById('customerEditSaveBtn');

            let obj = {
                name: nameValue,
                address: addressValue || null,
                description: descriptionValue || null,
            };

            let URL = '{{ url("/api/v1/customers") }}' + '/' + id;
            let token = localStorage.getItem('token');

            saveBtn.disabled = true;

            try {
                let response = await axios.put(URL, obj, { headers: { Authorization: 'Bearer ' + token } });

                if (response.data && response.data.success) {
                    showSuccessToast(response.data.message || 'Customer updated successfully.');
                    let modalEl = document.getElementById('customerEditModal');
                    let modal = window.bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    document.getElementById('customerEditForm').reset();
                    document.getElementById('customerEditId').value = '';
                    if (typeof getCustomers === 'function') getCustomers();
                } else {
                    showErrorToast(getErrorMessage(null, 'Failed to update customer.'));
                }
            } catch (err) {
                showErrorToast(getErrorMessage(err, 'Failed to update customer. Please try again.'));
            } finally {
                saveBtn.disabled = false;
            }
        }

        document.getElementById('customerEditForm').addEventListener('submit',async function(e){
            e.preventDefault();
            await doEditCustomer();
        });
    </script>
@endpush
