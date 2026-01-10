<?php

namespace App\Services;

use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SupplierService
{
    /**
     * Get paginated suppliers with search, sorting, and filtering.
     *
     * @param string $search
     * @param string $sortField
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getSuppliers(
        string $search = '',
        string $sortField = 'name',
        string $sortDirection = 'asc',
        int $perPage = 10
    ): LengthAwarePaginator {
        return Supplier::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);
    }

    /**
     * Create a new supplier.
     *
     * @param array $data
     * @param UploadedFile|null $image
     * @return Supplier
     */
    public function create(array $data, ?UploadedFile $image = null): Supplier
    {
        if ($image) {
            $data['image'] = $image->store('suppliers', 'public');
        }

        return Supplier::create($data);
    }

    /**
     * Update an existing supplier.
     *
     * @param Supplier $supplier
     * @param array $data
     * @param UploadedFile|null $image
     * @return bool
     */
    public function update(Supplier $supplier, array $data, ?UploadedFile $image = null): bool
    {
        // Handle image upload if a new image is provided
        if ($image && is_object($image) && method_exists($image, 'store')) {
            // Delete old image if it exists
            if ($supplier->image) {
                Storage::disk('public')->delete($supplier->image);
            }
            $data['image'] = $image->store('suppliers', 'public');
        }

        return $supplier->update($data);
    }

    /**
     * Delete a supplier.
     *
     * @param Supplier $supplier
     * @return bool
     */
    public function delete(Supplier $supplier): bool
    {
        return $supplier->delete();
    }
}
