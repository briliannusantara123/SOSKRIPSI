
                                <?php $no = 1; ?>
                                <?php foreach ($itempackage as $d): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($d->description) ?></td>
                                        <td id="stockstatus<?= $d->idi ?>">
                                            <a href="javascript:void(0);" class="update-stock-status" data-id="<?= $d->idi ?>" data-status="<?= $d->need_stock ?>">
                                                <label style="background-color: <?= $d->need_stock == 1 ? '#198754' : 'red' ?>; border-radius: 20px; color: white; padding: 10px;">
                                                    <?= $d->need_stock == 1 ? 'Limited' : 'Unlimited' ?>
                                                </label>
                                            </a>
                                            <input type="hidden" name="needstock" id="flagns<?= $d->idi ?>" value="<?= $d->need_stock ?>">
                                        </td>
                                        <td>
                                            <input type="number" name="stock" id="stock<?= $d->idi ?>" value="<?= $d->stock ?>" class="form-control" style="width: 80px;" oninput="handleInput(<?= $d->idi ?>)">
                                        </td>
                                        <td id="status<?= $d->idi ?>">
                                            <a href="javascript:void(0);" class="update-status" data-id="<?= $d->idi ?>" data-status="<?= $d->is_sold_out ?>">
                                                <label style="background-color: <?= $d->is_sold_out == 0 ? '#198754' : 'red' ?>; border-radius: 20px; color: white; padding: 10px;">
                                                    <?= $d->is_sold_out == 0 ? 'Available' : 'Sold Out' ?>
                                                </label>
                                            </a>
                                        </td>
                                        <td>
                                            <label style="background-color: <?= $d->is_active == 1 ? '#198754' : 'red' ?>; border-radius: 20px; color: white; padding: 10px;">
                                                <?= $d->is_active == 1 ? 'Active' : 'Inactive' ?>
                                            </label>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" 
                                               class="edit-item-package" 
                                               data-id="<?= $d->id ?>" 
                                               data-description="<?= htmlspecialchars($d->description) ?>" 
                                               data-item-code="<?= $d->item_code ?>"
                                               data-varian-category="<?= $d->varian_category ?>"
                                               data-max-qty="<?= $d->max_qty ?>">
                                               <i class="fas fa-pen" style="color: orange; font-size: 20px;"></i>
                                            </a>

                                            <a href="javascript:void(0);" class="delete-item-package" data-id="<?= $d->id ?>">
                                                <i class="fas fa-trash" style="color: red; font-size: 20px;"></i>
                                            </a>

                                        </td>
                                    </tr>
                                <?php endforeach ?>

