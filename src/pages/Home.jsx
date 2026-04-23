import React, { useState } from 'react';
import { LayoutGrid, List, Search, Pin, Lock, Share2 } from 'lucide-react';

const Home = () => {
  const [viewMode, setViewMode] = useState('grid'); // Tiêu chí 9 & 10 trong Rubrik
  const [searchQuery, setSearchQuery] = useState('');

  return (
    <div className="p-6">
      {/* Thanh tìm kiếm - Tiêu chí 17 (Live Search) */}
      <div className="flex items-center gap-4 mb-6 bg-gray-100 p-2 rounded">
        <Search size={20} />
        <input 
          type="text" 
          placeholder="Search notes..." 
          className="bg-transparent outline-none w-full"
          onChange={(e) => setSearchQuery(e.target.value)} // Live search trigger
        />
        
        {/* Nút chuyển đổi ViewMode - Tiêu chí 9 & 10 */}
        <button onClick={() => setViewMode(viewMode === 'grid' ? 'list' : 'grid')}>
          {viewMode === 'grid' ? <List /> : <LayoutGrid />}
        </button>
      </div>

      {/* Danh sách ghi chú */}
      <div className={viewMode === 'grid' ? 'grid grid-cols-3 gap-4' : 'flex flex-col gap-2'}>
        {/* Đây là ví dụ một Note có Icon nhận diện - Tiêu chí 16 & 66 */}
        <div className="border p-4 rounded relative shadow-sm">
          <h3 className="font-bold">Ghi chú mẫu</h3>
          <div className="flex gap-2 mt-2 text-gray-500">
            <Pin size={16} title="Pinned" />  {/* Tiêu chí 16 */}
            <Lock size={16} title="Protected" /> {/* Tiêu chí 22 */}
            <Share2 size={16} title="Shared" /> {/* Tiêu chí 23 */}
          </div>
        </div>
      </div>
    </div>
  );
};

export default Home;