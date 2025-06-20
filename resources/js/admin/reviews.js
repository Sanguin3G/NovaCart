const $ = window.jQuery;

$(function () {
    const table = $('#reviews-table').DataTable({
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/reviews/data',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function (d) {
                d.status_filter = $('#status-filter').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'product', name: 'product.name'},
            {data: 'reviewer', name: 'reviewer_name'},
            {data: 'rating', name: 'rating', orderable: false, searchable: false},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false},
        ],
    });

    $('#status-filter').change(() => table.draw());
    $('#search-input').on('keyup', function () {
        table.search($(this).val()).draw();
    });

    // Detail modal
    $(document).on('click', '.view-btn', function () {
        const url = $(this).data('url');
        if (!url) return;

        $('#review-detail-modal').load(url, () => {
            const dialog = document.getElementById('review-detail-modal');
            if (dialog && typeof dialog.showModal === 'function') {
                dialog.showModal();
            }
        });
    });

    // Redraw table after status toggle completes (listen for custom loading-end which fires after api util)
    window.addEventListener('loading-end', () => {
        // Short debounce to ensure backend has processed
        setTimeout(() => table.draw(false), 200);
    });
});
