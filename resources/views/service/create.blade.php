<div class="modal fade" id="service-create" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <i class="fas fa-hammer mr-1"></i>
                    Create New Service
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="px-2">
                        <div class="form-group">
                            <label for="" class="required">Service Name</label>
                            <input type="text" name="name" required class="form-control" placeholder="Enter Service Name">
                        </div>
                        <div class="form-group">
                            <label for="type" class="required">Service Type</label>
                            <select class="form-control" name="type" required id="type">
                                <option value="" selected disabled>Select type</option>
                                <option value="subscription">Subscription</option>
                                <option value="on-demand">On Demand</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="validity" class="required">Service validity</label>
                            <select class="form-control" name="validity" required id="validity">
                                <option value="" selected disabled>Select validity</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>

    </div>

</div>
