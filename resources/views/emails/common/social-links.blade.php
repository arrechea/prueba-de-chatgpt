<tr>
    @if(isset($brand->twitter))
        <td mc:hideable="" align="center"
            valign="middle"
            style="padding-right:7px;">
            <a href="{{$brand->twitter ?? ''}}" target="_blank"><img
                    src="{{asset('images/emails/t-icon.png')}}"
                    width="11" height="10"
                    alt="#"
                    style="border: 0px; vertical-align: middle; width: 11px;">
            </a>
        </td>
    @endif
    @if(isset($brand->facebook))
        <td mc:hideable="" align="center"
            valign="middle"
            style="padding:0 7px;">
            <a href="{{$brand->facebook ?? ''}}" target="_blank">
                <img
                    src="{{asset('images/emails/f-icon.png')}}"
                    width="7" height="12" border="0"
                    alt="#"
                    style="border: 0px; vertical-align: middle; width: 7px;">
            </a>
        </td>
    @endif
    @if(isset($brand->youtube))
        <td mc:hideable="" align="center"
            valign="middle"
            style="padding:0 7px;">
            <a href="{{$brand->youtube ?? ''}}" target="_blank"><img
                    src="{{asset('images/emails/you-icon.png')}}"
                    border="0" width="15"
                    height="11" alt="#"
                    style="border: 0px; vertical-align: middle; width: 15px;">
            </a>
        </td>
    @endif

    @if(isset($brand->instagram))
        <td mc:hideable="" align="center"
            valign="middle"
            style="padding:0 7px;">
            <a href="{{$brand->instagram ?? ''}}" target="_blank"><img
                    src="{{asset('images/emails/ins-icon.png')}}"
                    border="0" width="11"
                    height="12" alt="#"
                    style="border: 0px; vertical-align: middle; width: 11px;">
            </a>
        </td>
    @endif
    @if(isset($brand->google_plus))
        <td mc:hideable="" align="center"
            valign="middle"
            style="padding:0 7px;">
            <a href="{{$brand->google_plus ?? ''}}" target="_blank"><img
                    src="{{asset('images/emails/g-icon.png')}}"
                    border="0" width="18"
                    height="11" alt="#"
                    style="border: 0px; vertical-align: middle; width: 18px;">
            </a>
        </td>
    @endif
    @if(isset($brand->linkedin))
        <td mc:hideable="" align="center"
            valign="middle"
            style="padding:0 7px;">
            <a href="{{$brand->linkedin ?? ''}}" target="_blank"><img
                    src="{{asset('images/emails/in-icon.png')}}"
                    border="0" width="12"
                    height="12" alt="#"
                    style="border: 0px; vertical-align: middle; width: 12px;">
            </a>
        </td>
    @endif
</tr>
