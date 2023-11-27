<tr class="footer-titles">
    <th style="text-align: right" class=""></th>
    <th style="text-align: right" class=""></th>
    <th style="text-align: right" class=""></th>
    <th style="text-align: right" class=""></th>
    <th class="back-result result-footer" style="text-align: right">{{__('users.ReturnPercentage')}}</th>
    <th class="age-result result-footer" style="text-align: right">{{__('users.Average')}}</th>
</tr>
<tr class="footer-contents">
    <th style="text-align: right" class=""></th>
    <th style="text-align: right" class=""></th>
    <th style="text-align: right" class=""></th>
    <th style="text-align: right" class=""></th>
    <th class="back-result result-footer" style="text-align: right">{{round($back_percentage,1).'%' ?? ''}}</th>
    <th class="age-result result-footer" style="text-align: right">{{round($age_average,1) ?? ''}}</th>
</tr>
