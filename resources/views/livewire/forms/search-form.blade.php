<div>
    <style>
        .search-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: auto;
        }

        .search-container .dropdown-menu {
            width: 100%;
            display: block;
            max-height: 400px;
            overflow-y: auto;
            z-index: 10;
        }

        .search-container .list-group-item {
            cursor: pointer;
        }

        .search-container .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>
    <div class="search-container">
        <input type="text" wire:model.live.debounce.500ms="search" class="form-control rounded-3" id="searchInput"
            placeholder="Start Typing..." onkeyup="filterResults()">
        <div class="dropdown-menu p-0 mt-1" id="searchResults">
            <div class="list-group">
                @forelse ($results as $item)
                    <a href="#" class="list-group-item list-group-item-action">
                        <strong>{{ $item->company_name }}</strong><br>
                        <small>{{ $item->address }}</small>
                    </a>
                @empty
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function filterResults() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let results = document.getElementById("searchResults");
            let items = results.getElementsByClassName("list-group-item");

            let hasResults = false;
            for (let item of items) {
                let text = item.textContent.toLowerCase();
                if (text.includes(input) && input.trim() !== "") {
                    item.style.display = "block";
                    hasResults = true;
                } else {
                    item.style.display = "none";
                }
            }

            results.classList.toggle("d-none", !hasResults);
        }
    </script>
</div>
