<!-- Add Supervisor Modal -->
<div id="supervisorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    <h3 class="text-xl font-bold mb-4">Add New Supervisor</h3>
    <form id="addSupervisorForm" class="space-y-4">
      <input type="hidden" name="action" value="addSupervisor">
      <div>
        <label class="block mb-1">Full Name</label>
        <input type="text" name="name" class="w-full p-2 border rounded" required>
      </div>
      <div>
        <label class="block mb-1">Aadhar Number</label>
        <input type="text" name="aadhar" pattern="\d{12}" class="w-full p-2 border rounded" required>
      </div>
      <div>
        <label class="block mb-1">Contact Number</label>
        <input type="tel" name="contact" pattern="\d{10}" class="w-full p-2 border rounded" required>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" class="px-4 py-2 border rounded" onclick="closeModal('supervisorModal')">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-govt-blue text-white rounded">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Supervisor Modal -->
<div id="editSupervisorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    <h3 class="text-xl font-bold mb-4">Edit Supervisor</h3>
    <form id="editSupervisorForm" class="space-y-4">
      <input type="hidden" name="action" value="editSupervisor">
      <input type="hidden" name="id" id="editSupervisorId">
      <div>
        <label class="block mb-1">Full Name</label>
        <input type="text" name="name" id="editName" class="w-full p-2 border rounded" required>
      </div>
      <div>
        <label class="block mb-1">Aadhar Number</label>
        <input type="text" name="aadhar" id="editAadhar" pattern="\d{12}" class="w-full p-2 border rounded" required>
      </div>
      <div>
        <label class="block mb-1">Contact Number</label>
        <input type="tel" name="contact" id="editContact" pattern="\d{10}" class="w-full p-2 border rounded" required>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" class="px-4 py-2 border rounded" onclick="closeModal('editSupervisorModal')">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-govt-blue text-white rounded">Update</button>
      </div>
    </form>
  </div>
</div>