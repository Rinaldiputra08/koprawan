<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel16">
                {{ $type == 'mekanik' ? 'List Data Mekanik' : 'List Data Stall' }}
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="table_tampil_data">
                    <thead>
                        <tr>
                            <th colspan="2">
                                <input type="text" class="form-control search-data" name="search" id="search" autofocus>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="isi_data">
                        @if ($type == 'mekanik')
                            @php
                                $n = 0;
                            @endphp
                            @foreach ($list as $mekanik)
                                <tr id="tr" style="cursor: pointer; ">
                                    <td id="td" width='5%' name="td<?php echo $n; ?>"
                                        data-dismiss="modal" class="post" value="">
                                        {{ $mekanik->id }}
                                    </td>
                                    <td id="td" name="td<?php echo $n; ?>"
                                        data-dismiss="modal" class="post" value="">
                                        {{ $mekanik->nama }}
                                    </td>
                                </tr>
                                @php
                                    $n++;
                                @endphp
                            @endforeach
                        @endif

                        @if ($type == 'stall')
                            @php
                                $n = 0;
                            @endphp
                            @foreach ($list as $stall)
                                <tr id="tr" style="cursor: pointer; ">
                                    <td id="td" width='5%' name="td<?php echo $n; ?>"
                                        data-dismiss="modal" class="post" value="">
                                        {{ $stall->id }}
                                    </td>
                                    <td id="td" name="td<?php echo $n; ?>"
                                        data-dismiss="modal" class="post" value="">
                                        {{ $stall->nama_stall }}
                                    </td>
                                </tr>
                                @php
                                    $n++;
                                @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
