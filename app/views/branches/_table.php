<?php foreach ($branches as $branch): ?>
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($branch['name']); ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($branch['address']); ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($branch['phone']); ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($branch['email']); ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($branch['manager_name']); ?></td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $branch['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                <?php echo $branch['status'] === 'active' ? 'نشط' : 'غير نشط'; ?>
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <a href="/NMaterailManegmentT/public/index.php?controller=branch&action=edit&id=<?php echo $branch['id']; ?>"
               class="text-blue-600 hover:text-blue-900 ml-3">تعديل</a>
            <button onclick="deleteBranch(<?php echo $branch['id']; ?>)" class="text-red-600 hover:text-red-900">حذف</button>
        </td>
    </tr>
<?php endforeach; ?> 