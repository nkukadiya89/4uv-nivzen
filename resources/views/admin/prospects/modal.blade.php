<div class="modal fade" id="prospectModal" tabindex="-1" role="dialog" aria-labelledby="editProspectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prospectModal">Convert Prospect to Distributor/Kangen User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Convert <strong id="prospect_name"></strong> (<span id="prospect_email"></span>) to Distributor?</p>
                <form id="distributorConvertForm" action="{{ route('prospect-convert-distributor') }}" redirect="{{route('prospects-manage')}}">
                    <input type="hidden" id="prospect_id" name="prospect_id">


                    <div class="form-group">
                            <label for="enagic_id">Enagic Id</label>
                            <div>
                                <input id="enagic_id" type="text" class="form-control" name="enagic_id" placeholder="Enagic Id">
                            </div>
                    </div>
                    <div class="form-group">
                            <label for="type">User Type <small>(Machine Users / Distributors)</small><span class="required">*</span></label>
                            <div>
                                <select name="type" id="type" class="form-control city custom-select required" placeholder="User Type">
                                    <option value="">Select User Type</option>
                                    <option value="User">Kangen User</option>
                                    <option value="Distributor">Kangen Distributor</option>
                                </select>
                            </div>
                        </div>
                    <div class="form-group">
                        <label for="upline_id">Upline Name<span class="required">*</span></label>
                        <select name="upline_id" id="upline_id" class="form-control required" placeholder="Upline Name">
                            <!-- Fetch upline list dynamically -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="leader_id">Leader Name<span class="required">*</span></label>
                        <select name="leader_id" id="leader_id" class="form-control required" placeholder="Leader Name">
                            <!-- Fetch leader list dynamically -->
                        </select>

                    </div>
                    <button type="button" id="distributorConvertFormCancel" class="btn btn-secondary mr-2">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>



