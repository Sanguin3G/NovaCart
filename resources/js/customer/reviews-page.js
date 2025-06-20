import { Toast } from '../utils/toast.js';
import { api } from '../utils/api.js';

const $ = window.jQuery;

$(function () {
    // Tabs
    $('.tab-link').on('click', function () {
        const tab = $(this).data('tab');
        $('.tab-link').removeClass('border-orange-500 text-orange-600').addClass('border-transparent');
        $(this).removeClass('border-transparent').addClass('border-orange-500 text-orange-600');
        $('.tab-pane').addClass('hidden');
        $('#tab-' + tab).removeClass('hidden');
    });

    // DataTables
    const pendingTable = $('#pending-table').DataTable({
        dom: 'lfrt<"bottom"ip><"clear">',
        processing: true,
        serverSide: true,
        ajax: {
            url: '/my/reviews/pending/data',
            data: function(d){
                d.search_value = $('#pending-search').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'image', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'actions', orderable: false, searchable: false },
        ],
        raw: ['image', 'actions'],
    });

    const mineTable = $('#mine-table').DataTable({
        dom: 'lfrt<"bottom"ip><"clear">',
        processing: true,
        serverSide: true,
        ajax: {
            url: '/my/reviews/mine/data',
            data: function(d){
                d.search_value = $('#mine-search').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'image', orderable: false, searchable: false },
            { data: 'product', name: 'product.name' },
            { data: 'rating', name: 'rating', orderable: false, searchable: false },
            { data: 'actions', orderable: false, searchable: false },
        ],
        raw: ['image', 'rating', 'actions'],
    });

    // Modal handlers
    $(document).on('click', '.write-review-btn, .edit-review-btn', function () {
        const productId = $(this).data('product-id');
        const reviewId = $(this).data('review-id');
        const productName = $(this).data('product-name');
        const rating = $(this).data('rating') || 0;
        const body = $(this).data('body') || '';

        $('#review-modal-title').text(productName);
        $('#product-id-hidden').val(productId);
        $('#review-body').val(body);
        currentRating = rating;
        highlight(rating);
        $('#review-rating').val(rating);
        $('#review-modal').removeClass('hidden').addClass('flex');
    });

    // Star widget logic inside modal
    let currentRating = 0;
    const stars = document.querySelectorAll('#star-widget .star');
    function highlight(val) {
        stars.forEach(s => {
            if (s.dataset.value <= val) {
                s.classList.add('text-yellow-400');
                s.classList.remove('text-gray-300');
            } else {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-gray-300');
            }
        });
    }
    stars.forEach(star => {
        star.addEventListener('mouseenter', () => highlight(star.dataset.value));
        star.addEventListener('mouseleave', () => highlight(currentRating));
        star.addEventListener('click', () => {
            currentRating = star.dataset.value;
            $('#review-rating').val(currentRating);
        });
    });

    // Submit form
    $('#review-form').on('submit', async function (e) {
        e.preventDefault();
        const productId = $('#product-id-hidden').val();
        const rating = $('#review-rating').val();
        if (rating === '0') { Toast.info('Pick stars'); return; }
        const url = `/products/${productId}/reviews`;
        const formData = new FormData(this);
        try {
            await api.post(url, formData);
            Toast.success('Saved');
            $('#review-modal').addClass('hidden').removeClass('flex');
            pendingTable.ajax.reload();
            mineTable.ajax.reload();
        } catch (err) {
            Toast.error(err.message || 'Error');
        }
    });
}); 