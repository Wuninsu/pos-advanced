<div class="mb-2">
    @if (session('errorMsg'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <i style="margin-right:10px;" class="fa fa-ban"></i>
            <span>{{ session('errorMsg') }}</span>
        </div>
    @elseif (session('successMsg'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <i style="margin-right:10px;" class="fa fa-check"></i>
            <span> {{ session('successMsg') }}</span>
        </div>
    @elseif(session('warningMsg'))
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <i style="margin-right:10px;" class="fa fa-warning"></i>
            <span>{{ session('warningMsg') }}</span>
        </div>
    @elseif (session('infoMsg'))
        <div class="alert alert-info mb-0 alert-dismissible" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <i style="margin-right:10px;" class="fa fa-info-circle"></i> This is a
            <span>{{ session('infoMsg') }}</span>
        </div>
    @endif
</div>
