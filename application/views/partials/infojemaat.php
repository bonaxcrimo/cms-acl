<?php
    @$query=("SELECT * from tblmember where member_key=".$member_key);
    @$dataMember=queryCustom($query);
?>
    <table class="table tableBesuk">
        <thead>
            <tr>
                <th colspan="4">Data Info Member</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>memberno : </th>
                <td><?= @$dataMember->memberno ?></td>
                <th>handphone :</th>
                <td><?= @$dataMember->handphone ?></td>
            </tr>
            <tr>
                <th>membername : </th>
                <td><?= @$dataMember->membername ?></td>
                <th>address : </th>
                <td><?= @$dataMember->address ?></td>
            </tr>
            <tr>
                <th>chinesename : </th>
                <td><?= @$dataMember->chinesename ?></td>
                <th>city : </th>
                <td><?= @$dataMember->city ?></td>
            </tr>
        </tbody>
    </table>