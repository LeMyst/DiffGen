using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace xDiffPatcher
{
    public partial class frmProfiles : Form
    {
        public frmProfiles()
        {
            InitializeComponent();
        }

        private void frmProfiles_Load(object sender, EventArgs e)
        {
            patchList1.CheckBoxes = true;

            TreeNodeEx p1 = new TreeNodeEx("Patch1");
            TreeNodeEx p2 = new TreeNodeEx("Patch2");
            TreeNodeEx p3 = new TreeNodeEx("Patch3");
            p3.ActAsRadioGroup = true;
            p3.Nodes.Add("Patch 3a");
            p3.Nodes.Add("Patch 3b");
            p3.Nodes.Add("Patch 3c");
            TreeNodeEx p4 = new TreeNodeEx("Patch4");
            patchList1.Nodes.Add(p1);
            patchList1.Nodes.Add(p2);
            patchList1.Nodes.Add(p3);
            patchList1.Nodes.Add(p4);
        }
    }
}
