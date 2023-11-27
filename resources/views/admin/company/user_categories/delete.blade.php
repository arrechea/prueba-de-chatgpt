<form action="{{ route('admin.company.user_categories.delete.post', ['company' => $company, 'id' => $id]) }}"
      id="user-category-delete-form" method="post">
    {{ csrf_field() }}
    <input type="hidden" value="{{ $id }}" name="id">
    <h5 class="header" style="left: 35px;">{{ __('messages.delete-user-category') }}</h5>
</form>
