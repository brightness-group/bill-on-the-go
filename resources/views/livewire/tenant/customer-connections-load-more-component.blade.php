<div>
    @if(!empty($connections) && count($connections))
        <button wire:click="$emitSelf('loadMoreForConnectionsReport')" class="btn btn-primary d-none" id="load_more_connections_btn">
            Load More Connections
        </button>
    @endif
</div>
