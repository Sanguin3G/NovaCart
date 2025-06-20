import $ from 'jquery';
import 'datatables.net';

$(function () {
    const table = $('#todo-reviews-table').DataTable({
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        ajax: {
            url: route('reviews.todo'),
            type: 'GET',
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'image', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'actions', orderable: false, searchable: false },
        ],
        raw: ['image', 'actions'],
    });
}); 