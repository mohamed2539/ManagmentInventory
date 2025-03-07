<?php foreach ($materials as $material): ?>
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($material['code']); ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($material['name']); ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($material['description']); ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($material['quantity']); ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($material['unit']); ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($material['category_name']); ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($material['supplier_name']); ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <a href="/NMaterailManegmentT/public/index.php?controller=material&action=edit&id=<?php echo $material['id']; ?>"
               class="text-blue-600 hover:text-blue-900 ml-3">تعديل</a>
            <button onclick="deleteMaterial(<?php echo $material['id']; ?>)" 
                    class="text-red-600 hover:text-red-900">حذف</button>
        </td>
    </tr>
<?php endforeach; ?> 