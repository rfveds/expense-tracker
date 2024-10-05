import {Modal} from "bootstrap"
import {get, post, del} from "./ajax"
import DataTable from "datatables.net"

import "../css/transactions.scss"

window.addEventListener('DOMContentLoaded', function () {
    const newTransactionModal = new Modal(document.getElementById('newTransactionModal'))
    const editTransactionModal = new Modal(document.getElementById('editTransactionModal'))
    const uploadReceiptModal = new Modal(document.getElementById('uploadReceiptModal'))

    const table = new DataTable('#transactionsTable', {
        serverSide: true,
        ajax:       '/transactions/load',
        orderMulti: false,
        columns:    [
            {data: "description"},
            {
                data: row => new Intl.NumberFormat(
                    'en-US',
                    {
                        style:        'currency',
                        currency:     'USD',
                        currencySign: 'accounting'
                    }
                ).format(row.amount)
            },
            {data: "category"},
            // {
            //     data: row => {
            //         let icons = []
            //
            //         for (let i = 0; i < row.receipts.length; i++) {
            //             const receipt = row.receipts[i]
            //
            //             const span = document.createElement('span')
            //             const anchor = document.createElement('a')
            //             const icon = document.createElement('i')
            //             const deleteIcon = document.createElement('i')
            //
            //             deleteIcon.role = 'button'
            //
            //             span.classList.add('position-relative')
            //             icon.classList.add('bi', 'bi-file-earmark-text', 'download-receipt', 'text-primary', 'fs-4')
            //             deleteIcon.classList.add('bi', 'bi-x-circle-fill', 'delete-receipt', 'text-danger', 'position-absolute')
            //
            //             anchor.href = `/transactions/${row.id}/receipts/${receipt.id}`
            //             anchor.target = 'blank'
            //             anchor.title = receipt.name
            //
            //             deleteIcon.setAttribute('data-id', receipt.id)
            //             deleteIcon.setAttribute('data-transactionId', row.id)
            //
            //             anchor.append(icon)
            //             span.append(anchor)
            //             span.append(deleteIcon)
            //
            //             icons.push(span.outerHTML)
            //         }
            //
            //         return icons.join('')
            //     }
            // },
            {data: "date"},
            {
                sortable: false,
                data:     row => `
                    <div class="d-flex flex-">
                        <button type="submit" class="btn btn-outline-primary delete-transaction-btn" data-id="${row.id}">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                        <button class="ms-2 btn btn-outline-primary edit-transaction-btn" data-id="${row.id}">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="ms-2 btn btn-outline-primary open-receipt-upload-btn" data-id="${row.id}">
                            <i class="bi bi-upload"></i>
                        </button>
                    </div>
                `
            }
        ]
    })

    document.querySelector('#newTransactionModal').addEventListener('shown.bs.modal', function () {
        const categorySelect = this.querySelector('#categorySelect');
        loadCategories(categorySelect).catch(error => {
            console.error('Failed to load categories for new transaction:', error);
        });
    })

    document.querySelector('#editTransactionModal').addEventListener('shown.bs.modal', function () {
        const categorySelect = this.querySelector('#categorySelect');
        loadCategories(categorySelect).catch(error => {
            console.error('Failed to load categories for edit transaction:', error);
        });
    })

    document.querySelector('#transactionsTable').addEventListener('click', function (event) {
        const editBtn = event.target.closest('.edit-transaction-btn')
        const deleteBtn = event.target.closest('.delete-transaction-btn')
        const uploadReceiptBtn = event.target.closest('.open-receipt-upload-btn')
        const deleteReceiptBtn = event.target.closest('.delete-receipt')

        if (editBtn) {
            const transactionId = editBtn.getAttribute('data-id')

            get(`/transactions/${transactionId}`)
                .then(response => response.json())
                .then(response => openEditTransactionModal(editTransactionModal, response))
        } else if (deleteBtn) {
            const transactionId = deleteBtn.getAttribute('data-id')

            if (confirm('Are you sure you want to delete this transaction?')) {
                del(`/transactions/${transactionId}`).then(response => {
                    if (response.ok) {
                        table.draw()
                    }
                })
            }
        } else if (uploadReceiptBtn) {
            const transactionId = uploadReceiptBtn.getAttribute('data-id')

            uploadReceiptModal._element
                              .querySelector('.upload-receipt-btn')
                              .setAttribute('data-id', transactionId)

            uploadReceiptModal.show()
        } else if (deleteReceiptBtn) {
            const receiptId = deleteReceiptBtn.getAttribute('data-id')
            const transactionId = deleteReceiptBtn.getAttribute('data-transactionid')

            if (confirm('Are you sure you want to delete this receipt?')) {
                del(`/transactions/${transactionId}/receipts/${receiptId}`).then(response => {
                    if (response.ok) {
                        table.draw()
                    }
                })
            }
        }
    })

    document.querySelector('.create-transaction-btn').addEventListener('click', function (event) {
        post(`/transactions`, getTransactionFormData(newTransactionModal), newTransactionModal._element)
            .then(response => {
                if (response.ok) {
                    table.draw()

                    newTransactionModal.hide()
                }
            })
    })

    document.querySelector('.save-transaction-btn').addEventListener('click', function (event) {
        const transactionId = event.currentTarget.getAttribute('data-id')

        post(`/transactions/${transactionId}`, getTransactionFormData(editTransactionModal), editTransactionModal._element)
            .then(response => {
                if (response.ok) {
                    table.draw()
                    editTransactionModal.hide()
                }
            })
    })

    document.querySelector('.upload-receipt-btn').addEventListener('click', function (event) {
        const transactionId = event.currentTarget.getAttribute('data-id')
        const formData = new FormData()
        const files = uploadReceiptModal._element.querySelector('input[type="file"]').files

        for (let i = 0; i < files.length; i++) {
            formData.append('receipt', files[i])
        }

        post(`/transactions/${transactionId}/receipts`, formData, uploadReceiptModal._element)
            .then(response => {
                if (response.ok) {
                    table.draw()
                    uploadReceiptModal.hide()
                }
            })
    })
})

function getTransactionFormData(modal) {
    let data = {}
    const fields = [
        ...modal._element.getElementsByTagName('input'),
        ...modal._element.getElementsByTagName('select')
    ]

    fields.forEach(select => {
        data[select.name] = select.value
    })

    return data
}

async function openEditTransactionModal(modal, {id, ...data}) {
    try {
        const categorySelect = modal._element.querySelector('#categorySelect');
        await loadCategories(categorySelect);

        for (let name in data) {
            const input = modal._element.querySelector(`[name="${name}"]`)
            if (input) {
                input.value = data[name]
            }
        }

        modal._element.querySelector('.save-transaction-btn').setAttribute('data-id', id)

        modal.show()
    } catch (error) {
        console.error('Error opening edit transaction modal:', error);
    }
}

async function loadCategories(selectElement) {
    try {
        const response = await get('/categories/names');
        const categories = await response.json();

        selectElement.innerHTML = '';

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select Category';
        selectElement.appendChild(defaultOption);

        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            selectElement.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading categories:', error);
        throw error;
    }
}