import { Toast } from '../utils/toast.js';

window.addEventListener('DOMContentLoaded', () => {
    const $ = window.jQuery;
    if (!$ || !$.fn.DataTable) return;

    let ordersTable;

    $(document).on('click', '.view-orders-btn', function () {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        const $dialog = $('#orders-modal');
        if ($dialog.length === 0) return;

        const tableSelector = '#product-orders-table';

        if ($.fn.DataTable.isDataTable(tableSelector)) {
            ordersTable = $(tableSelector).DataTable();
            ordersTable.ajax.url(`/api/admin/products/${productId}/orders`).load();
        } else {
            ordersTable = $(tableSelector).DataTable({
                dom: 'lrtip',
                processing: true,
                serverSide: true,
                ajax: {
                    url: `/api/admin/products/${productId}/orders`,
                    type: 'GET',
                    data: (d) => {
                        d.status_filter = $('#orders-status-filter').val();
                        d.search_value = $('#orders-search-input').val();
                    },
                    error: (xhr) => {
                        if (xhr.status === 401 || xhr.status === 403) {
                            Toast.error('Unauthorized. Please log in as admin.');
                        } else {
                            Toast.error('Failed to load orders.');
                        }
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'order_number'},
                    {data: 'customer', defaultContent: ''},
                    {data: 'items_count'},
                    {data: 'total_amount'},
                    {data: 'status'},
                    {data: 'created_at'},
                    {data: 'actions', orderable: false, searchable: false},
                ],
            });

            $('#orders-status-filter').on('change', () => ordersTable.draw());
            $('#orders-search-input').on('keyup', () => ordersTable.draw());
        }

        // Update modal title
        $('#orders-modal-title').text(`Orders for \"${productName}\"`);

        $dialog.removeClass('hidden');
    });
});
